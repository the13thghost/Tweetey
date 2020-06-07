<x-master>
    <h1 class="text-center text-xl my-4 font-semibold text-blue-500">LOGIN</h1>
    
    <div class="flex justify-center">
        <form method="POST" action="{{ route('login') }}" class="border border-gray-200 rounded-lg p-8 bg-blue-100">
            @csrf
            <div class="mb-2">
                <label for="email" class="text-md">{{ __('E-Mail Address') }}</label>
                <div>
                    <input id="email" type="email" class="w-65 p-2 text-md mt-2 @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                    <span class="block text-sm text-red-400 font-normal" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>

            <div class="mb-2">
                <label for="password" class="text-md">{{ __('Password') }}</label>
                <div class="">
                    <input id="password" type="password" class="w-65 p-2 mt-2 @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="block text-sm text-red-400" role="alert">
                    {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="c">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="flex py-3">
                    <button type="submit"
                        class="w-full rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 shadow">
                        {{ __('Login') }}
                    </button>
                </div>
                @if (Route::has('password.request'))
                <a class="block text-xs text-gray-500 text-center hover:text-gray-700" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                @endif
            </div>
        </form>
    </div>
</x-master>
