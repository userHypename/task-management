<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gradient-to-b from-white to-yellow-50">
            <div class="w-full max-w-md">
                <div class="mb-6">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 rounded-full shadow-sm" />
                    @else
                        <div class="inline-block px-4 py-2 rounded-full border border-gray-200 text-lg font-semibold">{{ config('app.name', 'TaskManager') }}</div>
                    @endif
                </div>

                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">Sign in</h1>
                        <p class="text-sm text-gray-500 mt-1">Enter your credentials to access your workspace</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="sr-only">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email" class="w-full px-6 py-3 rounded-full border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Password" class="w-full px-6 py-3 rounded-full border border-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center text-sm text-gray-600">
                                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-yellow-500 shadow-sm focus:ring-yellow-400" />
                                <span class="ml-2">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:underline">Forgot?</a>
                            @endif
                        </div>

                        <div>
                            <button type="submit" class="w-full rounded-full py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold shadow-md">Submit</button>
                        </div>
                    </form>
                </div>

                @if (Route::has('register'))
                    <p class="text-center text-sm text-gray-500 mt-6">Don't have an account? <a href="{{ route('register') }}" class="text-yellow-500 hover:underline">Create an account</a></p>
                @endif
            </div>
        </div>

        <!-- Right: Illustration / decorative panel -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center relative overflow-hidden" style="background: linear-gradient(180deg, #fef3c7 0%, #fff 40%);">
            <div class="absolute inset-0 opacity-40" style="background-image: radial-gradient(circle at 10% 20%, rgba(255,230,179,0.6) 0%, transparent 20%), radial-gradient(circle at 80% 80%, rgba(255,245,157,0.5) 0%, transparent 25%);"></div>

            <div class="relative z-10 max-w-lg p-8 text-center">
                <h2 class="text-3xl font-semibold text-gray-900 mb-4">Create an account</h2>
                <p class="text-gray-600 mb-6">Sign up and get 30 day free trial</p>

                <div class="mx-auto w-56 h-36 bg-white rounded-xl shadow-lg overflow-hidden" aria-hidden="true">
                    <!-- decorative placeholder -->
                    <div class="w-full h-full bg-[url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1200&auto=format&fit=crop&ixlib=rb-4.0.3&s=placeholder')] bg-cover bg-center"></div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
