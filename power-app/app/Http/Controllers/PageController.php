<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Client;
use App\Models\Expertise;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Send visitors to the language their browser prefers.
     */
    public function redirectToLocale(Request $request): RedirectResponse
    {
        $locales = array_keys(config('app.locales'));

        return redirect()->route('home', ['locale' => $request->getPreferredLanguage($locales) ?? $locales[0]]);
    }

    public function home(): View
    {
        return view('pages.home', [
            'expertises' => Expertise::ordered()->get(),
            'featuredProject' => Project::published()->featured()->ordered()->first(),
            'latestPosts' => Post::latestPublished()->limit(3)->get(),
            'clients' => Client::visible()->ordered()->get(),
            'certificates' => Certificate::published()->ordered()->get(),
        ]);
    }

    public function certificates(): View
    {
        return view('pages.certificates', [
            'certificates' => Certificate::published()->ordered()->get(),
        ]);
    }

    public function expertises(): View
    {
        return view('pages.expertises', [
            'expertises' => Expertise::ordered()
                ->withCount(['projects' => fn ($query) => $query->published()])
                ->get(),
        ]);
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }
}
