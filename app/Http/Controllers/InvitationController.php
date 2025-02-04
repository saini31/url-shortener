<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ShortUrl;
use App\Models\Client;

class InvitationController extends Controller
{
    /**
     * Show invitations page (for superadmins/admins).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get filter inputs
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');

        // Base query for URLs
        $query = ShortUrl::query();

        if ($user->role === 'super_admin') {
            // Super Admin sees all URLs and all clients except super_admin
            $query->with('user');
            $clients = User::where('role', '!=', 'super_admin')->get();
        } elseif ($user->role === 'admin') {
            // Get emails of clients invited by this admin
            $invitedEmails = Invitation::where('admin_id', $user->id)->pluck('email');

            // Get user IDs of clients who registered with those emails (EXCLUDE Admin's own ID)
            $clientIds = User::whereIn('email', $invitedEmails)->pluck('id');

            // Admin sees their own URLs + URLs of their invited clients
            $query->whereIn('user_id', $clientIds->push($user->id));

            // Admin sees only their invited clients (EXCLUDING their own ID)
            $clients = User::whereIn('id', $clientIds)->where('id', '!=', $user->id)->get();
        } else {
            // Members only see their own URLs, no access to clients
            $query->where('user_id', $user->id);
            $clients = collect(); // Empty collection
        }

        // Apply date filters
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        if ($day) {
            $query->whereDay('created_at', $day);
        }

        // Fetch paginated results
        $urls = $query->paginate(10);

        // Fetch invitations once for efficiency
        $invitations = Invitation::whereIn('email', $clients->pluck('email'))->get()->keyBy('email');

        // Attach invitation status to each client (user)
        foreach ($clients as $client) {
            $client->invitation_status = $invitations[$client->email]->status ?? 'No Invitation';
        }

        return view('dashboard', compact('urls', 'invitations', 'clients'));
    }


    public function create()
    {
        return view('invitations.create');
    }
    /**
     * Send an invitation to a client via email.
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invitations,email',
            'role' => 'required|in:admin,member', // Ensure role is provided and valid
        ]);

        $token = Str::random(32);
        $expiresAt = Carbon::now()->addMinutes(30);


        $invitation = Invitation::create([
            'email' => $request->email,
            'role' => $request->role,
            'token' => $token,
            'expires_at' => $expiresAt,
            'admin_id' => Auth::user()->role === 'super_admin' ? null : Auth::id(), // If super admin, admin_id is null
        ]);

        $registrationUrl = route('register.invitation', ['token' => $token]);

        // Send invitation email
        Mail::send('mails.invitation', ['registrationUrl' => $registrationUrl], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('You have been invited to join URL Shortener');
        });

        return redirect()->route('invitations.index')->with('success', 'Invitation sent successfully!');
    }


    /**
     * Show the registration form for an invitation.
     */
    public function register($token)
    {

        $invitation = Invitation::where('token', $token)->where('expires_at', '>', now())->firstOrFail();

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
            'role' => $invitation->role, // Automatically set role from invitation
        ]);

        // Update the invitation status to 'accepted'
        $invitation->update(['status' => 'accepted']);
        // Delete invitation after successful registration
        // $invitation->delete();

        return redirect()->route('login')->with('success', 'Your account has been created successfully!');
    }
}
