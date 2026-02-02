<x-layouts.auth>
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            <div class="text-center text-sm mb-4">
                {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success shadow-lg mb-4 text-xs font-medium">
                    <span class="icon-[tabler--check] text-lg"></span>
                    <span>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</span>
                </div>
            @endif

            <div class="flex flex-col items-center justify-between space-y-4">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                    @csrf
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/30">
                        {{ __('Resend verification email') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm w-full opacity-70 hover:opacity-100"
                        data-test="logout-button">
                        {{ __('Log out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.auth>