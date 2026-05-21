<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display the user management page.
     */
    public function index()
    {
        // This query fetches users where the role is NOT 'admin'
        $users = User::where('role', '!=', 'admin')->get();

        return view('admin.users', compact('users'));
    }

    /*
     * Approve a pending user.
     */
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->decline_reason = null; // Clear any old reasons
        $user->save();

        return redirect()->route('admin.users.index', ['status' => $request->active_tab])
                         ->with('success', "User {$user->first_name} has been approved.");
    }

    /**
     * Decline a pending user registration.
     */
    public function decline(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->status = 'declined';
        $user->decline_reason = $request->comment;
        $user->save();

        return redirect()->route('admin.users.index', ['status' => $request->active_tab])
                         ->with('success', 'User registration declined.');
    }

    /**
     * Revoke access from an approved user.
     */
    public function revoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->status = 'revoked'; // This falls under the 'Declined' tab logic in JS
        $user->decline_reason = $request->comment;
        $user->save();

        return redirect()->route('admin.users.index', ['status' => $request->active_tab])
                         ->with('success', 'User access revoked.');
    }

    /**
     * Restore a declined or revoked user to Pending.
     */
    public function restore(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->status = 'pending';
        $user->decline_reason = null; 
        $user->save();

        return redirect()->route('admin.users.index', ['status' => $request->active_tab])
                         ->with('success', 'User restored to pending status.');
    }

    /**
     * Archive/Delete a user record.
     */
    public function archive(Request $request) 
    {
        // Get the user_id from the hidden input field in your form
        $user = User::findOrFail($request->user_id);
        
        $user->delete();

        return back()->with('success', 'User archived successfully.');
    }
}