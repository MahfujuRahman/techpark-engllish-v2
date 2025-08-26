<?php

namespace App\Http\Controllers\Policy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Modules\Management\WebsiteManagement\CookiePolicy\Models\Model as CookiePolicy;
use  App\Modules\Management\WebsiteManagement\RefundPolicy\Models\Model as RefundPolicy;
use  App\Modules\Management\WebsiteManagement\TermConditionPolicy\Models\Model as TermConditionPolicy;
use  App\Modules\Management\WebsiteManagement\PrivacyPolicy\Models\Model as PrivacyPolicy;

class PolicyController extends Controller
{
    public function privacy_policy()
    {
        $website_about = PrivacyPolicy::where('status', 1)->first();
        return view('frontend.pages.extra.privacy_policy', compact('website_about'));
    }

    public function refund_policy()
    {
        $website_about = RefundPolicy::where('status', 1)->first();
        return view('frontend.pages.extra.refund_policy', compact('website_about'));
    }

    public function terms_policy()
    {
        $website_about = TermConditionPolicy::where('status', 1)->first();
        return view('frontend.pages.extra.terms_policy', compact('website_about'));
    }

    public function cookies_policy()
    {
        $website_about = CookiePolicy::where('status', 1)->first();
        return view('frontend.pages.extra.cookies_policy', compact('website_about'));
    }
}
