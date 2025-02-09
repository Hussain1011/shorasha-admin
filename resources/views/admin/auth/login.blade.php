<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
            rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    </head>

<body>
    <div class="bg-[#F3FEFF] w-screen h-screen relative p-14 flex gap-28 justify-center">
        <a href="/" class="absolute start-20 top-14 hidden md:block"> <img
                src="{{ asset('images/images/logo.png') }}" width="245px" alt="Shorasha logo"></a>
        <div class="shadow-[0px_3px_20px_0px_rgba(0,_0,_0,_0.14)] rounded-[2.125rem] overflow-hidden h-[50rem]">
            <img class="h-full object-cover" src="{{ asset('images/images/login-background.png') }}" alt="background">
        </div>
        <div
            class="w-[90%] md:w-[35rem] absolute end-[5%] md:end-[15%] h-[40rem] top-[8.5rem] bg-white shadow-[0px_3px_20px_0px_rgba(0,_29,_39,_0.08)] rounded-[2.125rem] border border-[#D2D2D2] p-10">
            <form class="flex flex-col justify-between h-full" action="{{ route('login') }}" method="post">
                @method('POST') @csrf
                <div>
                    <p class="text-darkBlue font-bold text-[1.875rem] mb-5">Sign In</p>
                    <p class="text-[#808080] mb-8">Enter Your phone number and password to access admin panel.</p>
                    <div class="mb-6">
                        <label for="username" class="text-darkGray mb-3 block">Email</label>
                        <input type="text" id="username"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full"
                            placeholder="Enter Your Email" required  name="email"  value="{{ old('email') }}"/>
                        @error('email')
                            <span class="text-tiny+  text-error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="text-darkGray mb-3 block">Password</label>
                        <input type="password" id="password" name="password"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full"
                            placeholder="•••••••••" required />
                            @error('password')
                                <span class="text-tiny+ text-error"  style="color: red">{{ $message }}</span>
                            @enderror
                    </div>
                    {{-- <div class="flex justify-end">
            <a href="verification.html" class="text-main">Forget Password?</a>
          </div> --}}
                </div>
                <button type="submit"
                    class="text-white font-bold text-xl text-center py-3 bg-main rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)]">Submit</button>
            </form>

        </div>
    </div>
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</body>

</html>
