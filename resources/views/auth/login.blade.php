<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaTech Payroll</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="relative min-h-screen overflow-hidden">

<img src="{{ asset('images/lastt.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover">

<div class="relative z-10 min-h-screen grid lg:grid-cols-2 overflow-hidden">

    <!-- LEFT SIDE -->

    <div class="hidden lg:flex items-center justify-center p-0 overflow-hidden">


    </div>

    <!-- RIGHT SIDE -->

    <div class="flex items-center justify-center p-8 min-h-screen">

        <div class="w-full max-w-md p-10 bg-gray-100 rounded-2xl shadow-xl"><!-- container -->

            <h2 class="text-4xl font-bold text-slate-800 text-center">

                Welcome 

            </h2>

            <p class="text-slate-500 text-center mt-1 text-sm">
                Sign in to continue
            </p>

            @if($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <p class="font-semibold">Login error</p>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Administrator Form (only) --}}

            <form
                id="adminForm"
                method="POST"
                action="{{ route('login.admin') }}"
                class="mt-8">

                @csrf

                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-900">

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                <button
                    class="mt-6 w-full bg-blue-900 hover:bg-blue-950 text-white rounded-xl py-3 font-semibold transition">

                    Login

                </button>

            </form>

        </div>

    </div>

</div>

<script>
// Single form page — no tab toggles needed.

</script>

</body>

</html>