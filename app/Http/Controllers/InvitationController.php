<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\ShortUrl;

class InvitationController extends Controller
{
    /**
     * Show invitations page (for superadmins/admins) with caching.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Cache key based on user role and filter params
        $cacheKey = "invitations_{$user->id}_{$request->year}_{$request->month}_{$request->day}";

        // Try to get cached data, if not available fetch from DB
        $data = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($user, $request) {
            // Get filter inputs
            $year = $request->input('year');
            $month = $request->input('month');
            $day = $request->input('day');

            // Base query for URLs
            $query = ShortUrl::query();

            if ($user->role === 'super_admin') {
                // Super Admin sees all URLs and clients except super_admin
                $query->with('user');
                $clients = User::where('role', '!=', 'super_admin')->get();
            } elseif ($user->role === 'admin') {
                $invitedEmails = Invitation::where('admin_id', $user->id)->pluck('email');
                $clientIds = User::whereIn('email', $invitedEmails)->pluck('id');
                $query->whereIn('user_id', $clientIds->push($user->id));
                $clients = User::whereIn('id', $clientIds)->where('id', '!=', $user->id)->get();
            } else {
                $query->where('user_id', $user->id);
                $clients = collect();
            }

            // Apply date filters
            if ($year) $query->whereYear('created_at', $year);
            if ($month) $query->whereMonth('created_at', $month);
            if ($day) $query->whereDay('created_at', $day);

            // Fetch paginated results
            $urls = $query->paginate(10);

            // Fetch invitations once for efficiency
            $invitations = Invitation::whereIn('email', $clients->pluck('email'))->get()->keyBy('email');

            // Attach invitation status to each client (user)
            foreach ($clients as $client) {
                $client->invitation_status = $invitations[$client->email]->status ?? 'No Invitation';
            }

            return compact('urls', 'invitations', 'clients');
        });

        return view('dashboard', $data);
    }

    /**
     * Send an invitation to a client via email.
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invitations,email',
            'role' => 'required|in:admin,member',
        ]);

        $token = Str::random(32);
        $expiresAt = Carbon::now()->addMinutes(30);

        $invitation = Invitation::create([
            'email' => $request->email,
            'role' => $request->role,
            'token' => $token,
            'expires_at' => $expiresAt,
            'admin_id' => Auth::user()->role === 'super_admin' ? null : Auth::id(),
        ]);

        $registrationUrl = route('register.invitation', ['token' => $token]);

        // Send invitation email
        Mail::send('mails.invitation', ['registrationUrl' => $registrationUrl], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('You have been invited to join URL Shortener');
        });
           
        // Invalidate cache after sending an invitation
        Cache::flush();

        return redirect()->route('invitations.index')->with('success', 'Invitation sent successfully!');
    }
    public function create()
    {
        return view('invitations.create');
    }




    /**
     * Show the registration form for an invitation.
     */
    public function register($token)
    {
        $invitation = Cache::remember("invitation_{$token}", now()->addMinutes(30), function () use ($token) {
            return Invitation::where('token', $token)->where('expires_at', '>', now())->firstOrFail();
        });

        return view('auth.register', compact('invitation'));
    }

    /**
     * Handle the registration for an invitation.
     */
    public function registerSubmit(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Create user with the invitation email & role
        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => bcrypt($request->password),
            'role' => $invitation->role,
        ]);

        // Update invitation status
        $invitation->update(['status' => 'accepted']);

        // Invalidate cache
        Cache::flush();

        return redirect()->route('login')->with('success', 'Your account has been created successfully!');
    }
}
