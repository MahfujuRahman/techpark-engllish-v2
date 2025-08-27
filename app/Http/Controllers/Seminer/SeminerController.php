<?php

namespace App\Http\Controllers\Seminer;

use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Modules\Management\SeminerManagement\Seminer\Models\Model as Seminars;
use App\Modules\Management\SeminerManagement\SeminerParticipant\Models\Model as SeminarParticipants;
use App\Modules\Management\SeminerManagement\SeminerSubscribers\Models\Model as SeminarSubscriptions;
use App\Modules\Management\SeminerManagement\SeminerReviews\Models\Model as SeminarReviews;

class SeminerController extends Controller
{
    public function seminar()
    {
        $seminars = Seminars::where('date_time', '>', Carbon::today())->where('status', 'active')->get();
        return view('frontend.pages.seminer.seminar', compact('seminars'));
    }

    public function seminar_details($id)
    {
        // load seminar and its top-level reviews
        $seminar = Seminars::with(['reviews' => function ($q) {
            $q->orderBy('id', 'desc');
        }])->find($id);

        $seminars = Seminars::where('date_time', '>', Carbon::today())
            ->where('status', 'active')
            ->where('id', '!=', $seminar->id)
            ->get();

        // For each top-level review, attach replies from the JSON column and any legacy child rows
        $reviewModel = SeminarReviews::class;
        foreach ($seminar->reviews as $review) {
            // replies from JSON column
            $jsonReplies = [];
            if (!empty($review->comment_reply)) {
                $jsonReplies = json_decode($review->comment_reply, true) ?: [];
            }

            // legacy replies stored as separate rows (parent_id)
            $legacyReplies = [];
            try {
                $legacy = $reviewModel::where('id', $review->id)->orderBy('id', 'asc')->get();
                foreach ($legacy as $lr) {
                    $legacyReplies[] = [
                        'id' => $lr->id,
                        'name' => $lr->name,
                        'email' => $lr->email,
                        'user_id' => $lr->user_id,
                        'comment' => $lr->comment,
                        'rating' => $lr->rating,
                        'created_at' => optional($lr->created_at)->toDateTimeString(),
                    ];
                }
            } catch (\Exception $e) {
                $legacyReplies = [];
            }

            $all = array_merge($jsonReplies, $legacyReplies);
            // attach as collection of simple objects so the view partial can iterate
            $review->replies = collect($all)->map(function ($r) {
                return (object) $r;
            });
        }

        return view('frontend.pages.seminer.seminar-details', compact('seminar', 'seminars'));
    }

    public function registerSeminar()
    {
        $validator = Validator::make(request()->all(), [
            'full_name' => ['required'],
            'phone_number' => ['required'],
            'email' => ['email', 'nullable'],
            'address' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->back()->with('error', 'Validation error occurred.');
        }

        $seminar = new SeminarParticipants();
        $seminar->seminar_id = request()->seminar_id;
        $seminar->full_name = request()->full_name;
        $seminar->email = request()->email;
        $seminar->phone_number = request()->phone_number;
        $seminar->address = request()->address;
        $seminar->save();

        return response()->json(['message' => 'Registration for the seminar completed'], 200);
    }

    public function subscribe()
    {
        $validator = Validator::make(request()->all(), [
            'seminar_id' => ['required', 'exists:seminers,id'],
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Save the subscription
        $subscription = new SeminarSubscriptions();
        $subscription->seminar_id = request()->seminar_id;
        $subscription->email = request()->email;
        $subscription->save();

        return redirect()->back()->with('success', 'You have successfully subscribed to the seminar!');
    }

    public function review()
    {
        // Make validation rules conditional: if user is guest, require name/email
        $rules = [
            'seminar_id' => ['required', 'exists:seminers,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string'],
        ];
        if (!auth()->check()) {
            $rules['name'] = ['required', 'string'];
            $rules['email'] = ['required', 'email'];
        }

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Save the top-level review. Initialize comment_reply as empty JSON array.
        $review = new SeminarReviews();
        $review->seminar_id = request()->seminar_id;
        $review->name = auth()->user()->first_name ?? request()->name;
        $review->email = auth()->user()->email ?? request()->email;
        $review->user_id = auth()->id();
        $review->rating = request()->rating;
        $review->comment = request()->comment;
        $review->comment_reply = json_encode([]);
        $review->save();

        return redirect()->back()->with('success', 'You have successfully submitted your review!');
    }

    public function review_reply($id)
    {
        // Expect parent_id in request (compatible with older review_id param)
        $parentId = request()->parent_id ?? request()->review_id ?? null;

        $rules = [
            'comment' => ['required', 'string'],
        ];
        if (!auth()->check()) {
            $rules['name'] = ['required', 'string'];
            $rules['email'] = ['required', 'email'];
        }

        // If parentId is a numeric DB id, validate existence. If it's a JSON reply id (r_...), skip exists check.
        if (is_numeric($parentId)) {
            $rules['parent_id'] = ['required', 'exists:seminer_reviews,id'];
        } else {
            $rules['parent_id'] = ['required', 'string'];
        }

        $validator = Validator::make(array_merge(request()->all(), ['parent_id' => $parentId]), $rules);
        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // We need to support replying to either:
        // - a DB row (parent_id points to a SeminarReviews id)
        // - a nested JSON reply id (our JSON replies use string ids like r_...)

        // Try direct DB row first
        $parent = SeminarReviews::find($parentId);
        $replyData = [
            'id' => uniqid('r_'),
            'name' => auth()->user()->first_name ?? request()->name,
            'email' => auth()->user()->email ?? request()->email,
            'user_id' => auth()->id(),
            'rating' => 0,
            'comment' => request()->comment,
            'created_at' => now()->toDateTimeString(),
            'replies' => [],
        ];

        if ($parent) {
            // append as top-level parent's JSON replies
            $existing = [];
            if (!empty($parent->comment_reply)) {
                $existing = json_decode($parent->comment_reply, true) ?: [];
            }
            $existing[] = $replyData;
            $parent->comment_reply = json_encode($existing);
            $parent->save();
            return redirect()->back()->with('success', 'You have successfully submitted your reply!');
        }

        // If not a DB row, search through top-level reviews to find which one contains this JSON id
        $topLevel = SeminarReviews::where('seminar_id', request()->seminar_id)->get();
        foreach ($topLevel as $tl) {
            $payload = [];
            if (!empty($tl->comment_reply)) {
                $payload = json_decode($tl->comment_reply, true) ?: [];
            }

            // attempt recursive insert by reference
            if ($this->insertReplyIntoPayload($payload, $parentId, $replyData)) {
                $tl->comment_reply = json_encode($payload);
                $tl->save();
                return redirect()->back()->with('success', 'You have successfully submitted your reply!');
            }
        }

        return redirect()->back()->with('error', 'Parent not found for nested reply');
    }

    /**
     * Recursively search the payload (array of nodes) by reference and insert replyData into the node with id == parentId.
     * Returns true if inserted.
     */
    protected function insertReplyIntoPayload(array & $nodes, $parentId, array $replyData)
    {
        foreach ($nodes as $idx => & $node) {
            if (isset($node['id']) && $node['id'] == $parentId) {
                if (!isset($node['replies']) || !is_array($node['replies'])) {
                    $node['replies'] = [];
                }
                $node['replies'][] = $replyData;
                return true;
            }
            if (isset($node['replies']) && is_array($node['replies'])) {
                if ($this->insertReplyIntoPayload($node['replies'], $parentId, $replyData)) {
                    return true;
                }
            }
        }
        return false;
    }
}
