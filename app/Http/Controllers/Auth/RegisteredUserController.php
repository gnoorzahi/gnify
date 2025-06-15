<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $currentTenant = app('current_tenant');
        return view('auth.register', compact('currentTenant'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $currentTenant = app('current_tenant');
        
        // Enhanced validation with tenant-specific rules
        $rules = [
            'first_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email:rfc,dns', 
                'max:255',
                Rule::unique('users')->where(function ($query) use ($currentTenant) {
                    if ($currentTenant) {
                        return $query->where('tenant_id', $currentTenant->id);
                    }
                    return $query->whereNull('tenant_id');
                })
            ],
            'password' => [
                'required', 
                'confirmed', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
            'phone' => ['nullable', 'string', 'regex:/^[+]?[1-9][\d\s\-\(\)]{8,15}$/'],
            'terms' => ['required', 'accepted'],
        ];

        // Add additional validation messages
        $messages = [
            'first_name.regex' => 'First name may only contain letters and spaces.',
            'last_name.regex' => 'Last name may only contain letters and spaces.',
            'email.email' => 'Please enter a valid email address.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'phone.regex' => 'Please enter a valid phone number.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ];

        $request->validate($rules, $messages);

        // Create user with enhanced data
        $userData = [
            'name' => trim($request->first_name . ' ' . $request->last_name),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'email_verified_at' => null, // Will be set after email verification
        ];

        // Add tenant_id if we have a current tenant
        if ($currentTenant) {
            $userData['tenant_id'] = $currentTenant->id;
        }

        $user = User::create($userData);

        // Send email verification
        event(new Registered($user));

        // Don't automatically log in - require email verification first
        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }
}
