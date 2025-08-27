@php
    // Normalize $review for both Eloquent model and plain array/stdClass from JSON
    $r = $review;
    // if it's an array, convert to object
    if (is_array($r)) {
        $r = (object) $r;
    }

    $reviewId = $r->id ?? null;

    // name resolution: prefer user relation, then name field, else 'Anonymous'
    $reviewAuthor = 'Anonymous';
    if (isset($r->user) && is_object($r->user) && isset($r->user->first_name) && $r->user->first_name) {
        $reviewAuthor = $r->user->first_name;
    } elseif (!empty($r->name)) {
        $reviewAuthor = $r->name;
    }

    // created_at: if Carbon instance, use diffForHumans, else print string
    $created = '';
    if (!empty($r->created_at)) {
        try {
            if (is_object($r->created_at) && method_exists($r->created_at, 'diffForHumans')) {
                $created = $r->created_at->diffForHumans();
            } else {
                $created = \Carbon\Carbon::parse($r->created_at)->diffForHumans();
            }
        } catch (\Exception $e) {
            $created = (string) $r->created_at;
        }
    }

    $rating = $r->rating ?? null;
    $commentText = $r->comment ?? ($r->review ?? '');

    // ensure replies is a collection (or array) we can iterate
    $replies = collect();
    if (!empty($r->replies)) {
        // if replies is array or collection, normalize to collection of objects
        $replies = collect($r->replies)->map(function ($it) {
            if (is_array($it)) return (object) $it;
            return $it;
        });
    }
@endphp

<div class="review-item mb-3" id="review-{{ $reviewId ?? 'anon' }}">
    <div class="d-flex">
        <div class="flex-shrink-0">
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:44px;height:44px">{{ strtoupper(substr($reviewAuthor,0,1)) }}</div>
        </div>
        <div class="flex-grow-1 ms-3">
            <div class="d-flex align-items-center gap-2">
                <strong>{{ $reviewAuthor }}</strong>
                <small class="text-muted"> · {{ $created }}</small>
            </div>
            @if($rating)
                <div class="small text-warning">@for($i=0;$i<$rating;$i++)★@endfor</div>
            @endif
            <div class="mt-1">{{ $review->comment ?? '' }}</div>

            <div class="mt-2">
                <button class="btn btn-sm btn-link reply-toggle" data-id="{{ $reviewId ?? '' }}">Reply</button>
            </div>
        </div>
    </div>

    {{-- Replies container (recursive) --}}
    <div class="replies mt-3 ms-5">
        @if(!empty($replies) && $replies->count())
            @foreach($replies as $reply)
                @php
                    // Skip if reply duplicates the parent (sometimes happens when replies were merged into JSON)
                    $skip = false;
                    $rep = $reply;
                    if (is_array($rep)) $rep = (object) $rep;
                    if (!empty($rep->id) && !empty($reviewId) && $rep->id == $reviewId) {
                        $skip = true;
                    }
                    // or identical comment + timestamp
                    if (!$skip && isset($rep->comment) && isset($r->comment) && isset($rep->created_at) && isset($r->created_at)) {
                        if (trim((string)$rep->comment) === trim((string)$r->comment) && (string)$rep->created_at === (string)$r->created_at) {
                            $skip = true;
                        }
                    }
                @endphp
                @if(!$skip)
                    @include('frontend.pages.seminer.partials._review', ['review' => $reply])
                @endif
            @endforeach
        @endif
    </div>
</div>
