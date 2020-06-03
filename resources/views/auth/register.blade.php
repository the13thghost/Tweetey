<x-master>
                    <div class="text-center text-xl my-4 font-semibold text-blue-500">REGISTER</div>
    
                    <div class="flex justify-center">
                        <form method="POST" action="{{ route('register') }}" class="border border-gray-200 rounded-lg p-8 bg-blue-100">
                            @csrf
    
                            <div class="mb-2">
                                <label for="name" class="text-md">{{ __('Name') }}</label>
    
                                <div>
                                    <input id="name" type="text" class="w-65 p-2 text-md mt-1 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
    
                                    @error('name')
                                        <span class="block text-sm text-red-400 font-normal" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="username" class="text-md">Username</label>
    
                                <div>
                                    <input id="username" type="text" class="w-65 p-2 text-md mt-1 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
    
                                    @error('username')
                                        <span class="block text-sm text-red-400 font-normal" role="alert">
                                        {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="mb-2">
                                <label for="email" class="text-md">{{ __('E-Mail Address') }}</label>
    
                                <div>
                                    <input id="email" type="email" class="w-65 p-2 text-md mt-1 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
    
                                    @error('email')
                                        <span class="block text-sm text-red-400 font-normal" role="alert">
                                            {{ $message }}<
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="mb-2">
                                <label for="password" class="text-md">{{ __('Password') }}</label>
    
                                <div class="">
                                    <input id="password" type="password" class="w-65 p-2 text-md mt-1 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
    
                                    @error('password')
                                        <span class="block text-sm text-red-400 font-normal" role="alert">
                                        {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="mb-3">
                                <label for="password-confirm" class="text-md">{{ __('Confirm Password') }}</label>
    
                                <div>
                                    <input id="password-confirm" type="password" class="w-65 p-2 text-md mt-1 " name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
    
                            <div>
                                    <button type="submit" class="w-full rounded-full font-semibold bg-blue-500 px-6 py-2 text-white hover:bg-blue-400 shadow">
                                        {{ __('Register') }}
                                    </button>
                            </div>
                        </form>
                    </div>
</x-master>
