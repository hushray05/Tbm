<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Feature;
use App\Models\Page;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicController extends Controller
{
    private function redirectIfAuthenticated(): ?RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return null;
    }

    /**
     * Load the shared public pricing data (plans, features, and default trial).
     */
    private function pricingData(): array
    {
        $plans = SubscriptionPlan::with('planFeatures')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $features = Feature::where('is_active', true)->get();
        $defaultTrialDays = (int) SubscriptionSetting::get('default_trial_days', 30);

        return compact('plans', 'features', 'defaultTrialDays');
    }

    /**
     * Load active featured companies for the public showcase.
     */
    private function companiesData(): array
    {
        $companies = Company::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return compact('companies');
    }

    /**
     * Load a page's content by slug from the database.
     */
    private function pageData(string $slug): ?Page
    {
        return Page::where('slug', $slug)->where('is_active', true)->first();
    }

    /**
     * Public marketing homepage.
     */
    public function home(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfAuthenticated()) return $redirect;
        $page = $this->pageData('home');
        return view('public.home', array_merge($this->pricingData(), $this->companiesData(), compact('page')));
    }

    /**
     * Public product features overview.
     */
    public function features(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfAuthenticated()) return $redirect;
        $page = $this->pageData('features');
        return view('public.features', array_merge($this->pricingData(), $this->companiesData(), compact('page')));
    }

    /**
     * Public how-it-works guide.
     */
    public function howItWorks(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfAuthenticated()) return $redirect;
        $page = $this->pageData('how-it-works');
        return view('public.how-it-works', array_merge($this->companiesData(), compact('page')));
    }

    /**
     * Public company / about page.
     */
    public function about(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfAuthenticated()) return $redirect;
        $page = $this->pageData('about');
        return view('public.about', array_merge($this->companiesData(), compact('page')));
    }

    /**
     * Public frequently asked questions.
     */
    public function faq(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfAuthenticated()) return $redirect;
        $page = $this->pageData('faq');
        return view('public.faq', array_merge($this->companiesData(), compact('page')));
    }
}
