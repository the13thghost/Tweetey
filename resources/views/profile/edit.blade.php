<x-app>
    <section class="w-auto mb-6">
        <h3 class="text-lg font-semibold p-2 text-blue-500">Edit your profile</h3>
        <form action="/profile/{{$user->username}}" method="post" class="block p-3" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <label class="block text-lg" for="name">Name</label>
            <input class="w-full block p-2 bg-white border border-gray-300 mb-2" type="text" name="name" value="{{current_user()->name}}">
            @error('name')
            <p>{{$message}}</p>
            @enderror
            <label class="block text-lg" for="username">Username</label>
            <input class="w-full block p-2 bg-white border border-gray-300 mb-2" type="text" name="username" value="{{current_user()->username}}">
            @error('username')
            <p>{{$message}}</p>
            @enderror
            <label class="block text-lg" for="avatar">Avatar</label>
            <input class="w-full block p-2 bg-white border border-gray-300 mb-2" type="file" name="avatar">
            @error('avatar')
            <p>{{$message}}</p>
            @enderror
            <label class="block text-lg" for="cover">Cover photo</label>
            <input class="w-full block p-2 bg-white border border-gray-300 mb-2" type="file" name="cover">
            @error('cover')
            <p>{{$message}}</p>
            @enderror
            <label class="block text-lg" for="email">E-mail</label>
            <input class="w-full block p-2 bg-white border border-gray-300 mb-2" type="email" name="email" value="{{current_user()->email}}">
            @error('email')
            <p>{{$message}}</p>
            @enderror
            <label class="block text-lg" for="password">Password</label>
            <input class="w-full block p-2 bg-white border border-gray-300 mb-2" type="password" name="password">
            @error('password')
            <p>{{$message}}</p>
            @enderror
            <label class="block text-lg" for="password_confirmation">Password Confirmation</label>
            <input class="w-full block p-2 bg-white border border-gray-300 mb-5" type="password" name="password_confirmation">
            <x-blue-btn class="float-right inline">Update</x-blue-btn>
            <a href="{{route('profile', auth()->user()->username)}}" class="inline ml-3 hover:underline text-gray-600">Cancel</a>
        </form>
    <div>
    </div>
    </section>
</x-app>
