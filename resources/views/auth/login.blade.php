<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaTech Payroll</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen">

<div class="min-h-screen grid lg:grid-cols-2">

    <!-- LEFT SIDE -->

    <div
        class="hidden lg:flex flex-col justify-center bg-blue-900 text-white px-20">

        <h1 class="text-5xl font-bold leading-tight">

            NexaTech Payroll

        </h1>

        <p class="mt-6 text-lg text-blue-100">

            Payroll Management System

        </p>

        <div class="mt-16">

            <div class="w-24 h-1 bg-teal-500 rounded-full mb-6"></div>

            <p class="text-blue-100 leading-8">

                Secure.

                Professional.

                Fast.

                Built exclusively for Human Resource and Payroll Management.

            </p>

        </div>

    </div>

    <!-- RIGHT SIDE -->

    <div class="flex items-center justify-center p-8">

        <div class="bg-white w-full max-w-md rounded-3xl shadow-xl p-10">

            <h2 class="text-3xl font-bold text-slate-800 text-center">

                Welcome Back

            </h2>

            <p class="text-slate-500 text-center mt-2">
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

            <!-- Tabs -->

            <div
                class="flex mt-8 rounded-xl bg-slate-100 p-1">

                <button
                    id="employeeTab"
                    type="button"
                    class="w-1/2 rounded-lg py-2 bg-teal-600 text-white">

                    Employee

                </button>

                <button
                    id="adminTab"
                    type="button"
                    class="w-1/2 rounded-lg py-2">

                    Administrator

                </button>

            </div>

            {{-- Employee Form --}}

            <form
                id="employeeForm"
                method="POST"
                action="{{ route('login.employee') }}"
                class="mt-8">

                @csrf

                <input
                    type="text"
                    name="username"
                    placeholder="Username"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-teal-500">

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                <button
                    class="mt-6 w-full bg-teal-600 hover:bg-teal-700 text-white rounded-xl py-3 font-semibold transition">

                    Employee Login

                </button>

            </form>

            {{-- Admin Form --}}

            <form
                id="adminForm"
                method="POST"
                action="{{ route('login.admin') }}"
                class="hidden mt-8">

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

                    Administrator Login

                </button>

            </form>

        </div>

    </div>

</div>

<script>

const employeeTab=document.getElementById('employeeTab');
const adminTab=document.getElementById('adminTab');

const employeeForm=document.getElementById('employeeForm');
const adminForm=document.getElementById('adminForm');

employeeTab.onclick=()=>{

employeeForm.classList.remove('hidden');
adminForm.classList.add('hidden');

employeeTab.classList.add('bg-teal-600','text-white');
adminTab.classList.remove('bg-blue-900','text-white');

}

adminTab.onclick=()=>{

adminForm.classList.remove('hidden');
employeeForm.classList.add('hidden');

adminTab.classList.add('bg-blue-900','text-white');
employeeTab.classList.remove('bg-teal-600','text-white');

}

</script>

</body>

</html>