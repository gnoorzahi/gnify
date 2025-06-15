<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-semibold text-gray-900">Create Your Account</h2>
        @if(isset($currentTenant))
            <p class="text-sm text-gray-600 mt-1">Join {{ $currentTenant->name }}</p>
        @else
            <p class="text-sm text-gray-600 mt-1">Get started with your new account</p>
        @endif
    </div>

    <form method="POST" action="{{ route('register') }}" id="registration-form">
        @csrf

        <!-- First Name and Last Name -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">We'll send you a verification email</p>
        </div>

        <!-- Phone Number (Optional) -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number (Optional)')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" placeholder="+1 (555) 123-4567" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password with strength indicator -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                    <svg class="h-5 w-5 text-gray-400" id="password-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Password strength indicator -->
            <div class="mt-2">
                <div class="flex items-center space-x-2">
                    <div class="flex-1">
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div id="password-strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%;"></div>
                        </div>
                    </div>
                    <span id="password-strength-text" class="text-xs text-gray-500">Weak</span>
                </div>
                <div class="mt-1 text-xs text-gray-600">
                    <p>Password must contain:</p>
                    <ul class="list-disc list-inside ml-2 space-y-1">
                        <li id="length-check" class="text-gray-400">At least 8 characters</li>
                        <li id="uppercase-check" class="text-gray-400">One uppercase letter</li>
                        <li id="lowercase-check" class="text-gray-400">One lowercase letter</li>
                        <li id="number-check" class="text-gray-400">One number</li>
                        <li id="special-check" class="text-gray-400">One special character (@$!%*?&)</li>
                    </ul>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password_confirmation')">
                    <svg class="h-5 w-5 text-gray-400" id="password_confirmation-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <div id="password-match" class="mt-1 text-xs hidden">
                <span id="password-match-text"></span>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="mt-4">
            <label for="terms" class="flex items-start">
                <input id="terms" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mt-1" name="terms" required>
                <span class="ms-2 text-sm text-gray-600">
                    I agree to the 
                    <a href="#" class="underline text-indigo-600 hover:text-indigo-900">Terms of Service</a> 
                    and 
                    <a href="#" class="underline text-indigo-600 hover:text-indigo-900">Privacy Policy</a>
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>

            <x-primary-button class="ms-4" id="register-button" disabled>
                {{ __('Create Account') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                field.type = 'password';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        function updatePasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            // Check criteria
            const hasLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            
            // Update visual indicators
            updateCheck('length-check', hasLength);
            updateCheck('uppercase-check', hasUpper);
            updateCheck('lowercase-check', hasLower);
            updateCheck('number-check', hasNumber);
            updateCheck('special-check', hasSpecial);
            
            // Calculate strength
            const score = [hasLength, hasUpper, hasLower, hasNumber, hasSpecial].filter(Boolean).length;
            
            let strength, color, width;
            if (score < 2) {
                strength = 'Very Weak';
                color = '#ef4444';
                width = '20%';
            } else if (score < 3) {
                strength = 'Weak';
                color = '#f97316';
                width = '40%';
            } else if (score < 4) {
                strength = 'Fair';
                color = '#eab308';
                width = '60%';
            } else if (score < 5) {
                strength = 'Good';
                color = '#22c55e';
                width = '80%';
            } else {
                strength = 'Strong';
                color = '#16a34a';
                width = '100%';
            }
            
            strengthBar.style.backgroundColor = color;
            strengthBar.style.width = width;
            strengthText.textContent = strength;
            strengthText.style.color = color;
            
            updateFormValidation();
        }

        function updateCheck(id, passed) {
            const element = document.getElementById(id);
            if (passed) {
                element.className = 'text-green-600';
                element.innerHTML = element.innerHTML.replace('text-gray-400', 'text-green-600');
            } else {
                element.className = 'text-gray-400';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('password-match');
            const matchText = document.getElementById('password-match-text');
            
            if (confirmation.length > 0) {
                matchDiv.classList.remove('hidden');
                if (password === confirmation) {
                    matchText.textContent = '✓ Passwords match';
                    matchText.className = 'text-green-600';
                } else {
                    matchText.textContent = '✗ Passwords do not match';
                    matchText.className = 'text-red-600';
                }
            } else {
                matchDiv.classList.add('hidden');
            }
            
            updateFormValidation();
        }

        function updateFormValidation() {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;
            const registerButton = document.getElementById('register-button');
            
            // Check if password meets all criteria
            const hasLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            const passwordValid = hasLength && hasUpper && hasLower && hasNumber && hasSpecial;
            
            const formValid = firstName && lastName && email && passwordValid && 
                            (password === confirmation) && terms;
            
            registerButton.disabled = !formValid;
            registerButton.className = formValid 
                ? 'ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                : 'ms-4 inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed transition ease-in-out duration-150';
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', updatePasswordStrength);
        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);
        document.getElementById('first_name').addEventListener('input', updateFormValidation);
        document.getElementById('last_name').addEventListener('input', updateFormValidation);
        document.getElementById('email').addEventListener('input', updateFormValidation);
        document.getElementById('terms').addEventListener('change', updateFormValidation);

        // Initial validation
        updateFormValidation();
    </script>
</x-guest-layout>
