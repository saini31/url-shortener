<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Invitation;
use App\Models\User;

class ShortUrlController extends Controller
{
    /**
     * Show URLs based on role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            $urls = ShortUrl::with('user')->paginate(10);
        } elseif ($user->role === 'admin') {
            $invitedEmails = Invitation::where('admin_id', $user->id)->pluck('email');
            $clientIds = User::whereIn('email', $invitedEmails)->pluck('id');
            $urls = ShortUrl::whereIn('user_id', $clientIds->push($user->id))->paginate(10);
        } else {
            $urls = ShortUrl::where('user_id', $user->id)->paginate(10);
        }

        return view('dashboard', compact('urls'));
    }

    /**
     * Store a new short URL (Only Admin & Member).
     */
    public function store(Request $request)
    {
        $request->validate([
            'long_url' => 'required|url',
        ]);

        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'member'])) {
            return back()->withErrors(['error' => 'Only Admin and Members can create short URLs.']);
        }

        $existing = ShortUrl::where('user_id', $user->id)->where('long_url', $request->long_url)->first();
        if ($existing) {
            return back()->with('error', 'Short URL already exists: ' . url('/s/' . $existing->short_code));
        }

        do {
            $shortCode = Str::random(6);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        ShortUrl::create([
            'user_id' => $user->id,
            'long_url' => $request->long_url,
            'short_code' => $shortCode,
        ]);

        return back()->with('success', 'Short URL created: ' . url('/s/' . $shortCode));
    }

    /**
     * Redirect to the original URL when short URL is accessed.
     */
    public function redirect($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();
        $shortUrl->increment('hits');
        return redirect($shortUrl->long_url);
    }

    /**
     * Export short URLs based on role.
     */
    public function export()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            $urls = ShortUrl::with('user')->get();
        } elseif ($user->role === 'admin') {
            $invitedEmails = Invitation::where('admin_id', $user->id)->pluck('email');
            $clientIds = User::whereIn('email', $invitedEmails)->pluck('id');
            $urls = ShortUrl::whereIn('user_id', $clientIds->push($user->id))->get();
        } else {
            $urls = ShortUrl::where('user_id', $user->id)->get();
        }

        $csvData = fopen('php://memory', 'w');
        fputcsv($csvData, ['Short URL', 'Long URL', 'Hits', 'Created At', 'User Email']);
        foreach ($urls as $url) {
            fputcsv($csvData, [
                url('/s/' . $url->short_code),
                $url->long_url,
                $url->hits,
                $url->created_at->format('Y-m-d H:i'),
                optional($url->user)->email,
            ]);
        }
        rewind($csvData);

        return Response::stream(function () use ($csvData) {
            fpassthru($csvData);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="short_urls.csv"',
        ]);
    }
}
