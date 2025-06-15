<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
            </svg>
        </div>
        <h2 class="mt-4 text-xl font-bold text-gray-900">Verify Your Email</h2>
        <p class="mt-2 text-sm text-gray-600">
            We've sent you a verification link at
            <span class="font-medium text-gray-900">{{ Auth::user()->email }}</span>
        </p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Before getting started, please verify your email address by clicking on the link we just emailed to you. 
                    If you didn't receive the email, check your spam folder or click the button below to send another.
                </p>
            </div>
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        Verification email sent! Check your inbox for a new verification link.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
            @csrf
            <x-primary-button class="w-full justify-center" id="resend-button">
                <span id="resend-text">{{ __('Resend Verification Email') }}</span>
                <svg id="resend-spinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </x-primary-button>
        </form>

        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">Wrong email address?</p>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                    {{ __('Sign out and register again') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
        <h3 class="text-sm font-medium text-gray-900 mb-2">Having trouble?</h3>
        <ul class="text-xs text-gray-600 space-y-1">
            <li>• Check your spam or junk email folder</li>
            <li>• Make sure {{ config('app.name') }} emails aren't being blocked</li>
            <li>• Wait a few minutes for the email to arrive</li>
            <li>• Try resending the verification email</li>
        </ul>
    </div>

    <script>
        document.getElementById('resend-form').addEventListener('submit', function(e) {
            const button = document.getElementById('resend-button');
            const text = document.getElementById('resend-text');
            const spinner = document.getElementById('resend-spinner');
            
            button.disabled = true;
            text.textContent = 'Sending...';
            spinner.classList.remove('hidden');
            
            // Re-enable after 5 seconds
            setTimeout(() => {
                button.disabled = false;
                text.textContent = 'Resend Verification Email';
                spinner.classList.add('hidden');
            }, 5000);
        });
    </script>
</x-guest-layout>
