<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Auth\Authenticatable;

class UserController extends Controller
{
    /**
     * Get the authenticated user
     */
    private function getAuthUser(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * Check if user is admin
     */
    private function isAdmin(): bool
    {
        $user = $this->getAuthUser();
        return $user && $user->role === 'admin';
    }

    /**
     * Show all users (admin only)
     */
    public function index(Request $request)
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can view users.');
        }

        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by account status
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        $users = $query->with('department', 'manager')
                       ->latest()
                       ->paginate(15)
                       ->withQueryString();

        $departments = Department::all();
        $roles = ['admin', 'manager', 'employee'];

        return view('users.index', compact('users', 'departments', 'roles'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can create users.');
        }

        $departments = Department::all();
        $managers = User::where('role', 'manager')->orWhere('role', 'admin')->get();
        $roles = ['admin', 'manager', 'employee'];

        return view('users.create', compact('departments', 'managers', 'roles'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can create users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,employee',
            'department_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department_id' => $validated['department_id'] ?? null,
            'manager_id' => $validated['manager_id'] ?? null,
            'position' => $validated['position'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'account_status' => 'active',
        ]);

        return redirect()->route('users.show', $user)
                       ->with('success', 'User created successfully!');
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        // Manual authorization check
        $currentUser = $this->getAuthUser();
        
        // Admins can view anyone
        if ($currentUser && $currentUser->role === 'admin') {
            // Allow access
        }
        // Managers can view their team members
        elseif ($currentUser && $currentUser->role === 'manager' && $user->manager_id === $currentUser->id) {
            // Allow access
        }
        // Users can view themselves
        elseif ($currentUser && $currentUser->id === $user->id) {
            // Allow access
        }
        else {
            abort(403, 'Unauthorized - You cannot view this user profile.');
        }

        $user->load('department', 'manager', 'managedEmployees', 'createdTasks', 'assignedToManyTasks');

        return view('users.show', compact('user'));
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can edit users.');
        }

        $departments = Department::all();
        $managers = User::where('id', '!=', $user->id)
                        ->where(function ($query) {
                            $query->where('role', 'manager')
                                  ->orWhere('role', 'admin');
                        })
                        ->get();
        $roles = ['admin', 'manager', 'employee'];

        return view('users.edit', compact('user', 'departments', 'managers', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can update users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,manager,employee',
            'department_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'account_status' => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        return redirect()->route('users.show', $user)
                       ->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can delete users.');
        }

        // Prevent deleting yourself
        $currentUser = $this->getAuthUser();
        if ($currentUser && $user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('users.index')
                       ->with('success', 'User deleted successfully!');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can reset user passwords.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password reset successfully!');
    }

    /**
     * Toggle user account status
     */
    public function toggleStatus(User $user)
    {
        // Manual authorization check
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized - Only administrators can toggle user status.');
        }

        // Prevent disabling yourself
        $currentUser = $this->getAuthUser();
        if ($currentUser && $user->id === $currentUser->id) {
            return back()->with('error', 'You cannot disable your own account!');
        }

        $newStatus = $user->account_status === 'active' ? 'inactive' : 'active';
        $user->update(['account_status' => $newStatus]);

        $message = $newStatus === 'active' ? 'User activated successfully!' : 'User deactivated successfully!';

        return back()->with('success', $message);
    }
}