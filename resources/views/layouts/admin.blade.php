<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaTech Payroll</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-50">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white">

        <div class="p-6">

            <h1 class="text-2xl font-bold">
                NexaTech
            </h1>

            <p class="text-blue-200 text-sm">
                Payroll System
            </p>

        </div>

        <nav class="mt-8">

            <a href="{{ route('admin.dashboard') }}"
                class="block px-6 py-3 transition
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-teal-600 text-white'
                    : 'hover:bg-teal-600' }}">
                Overview
                </a>

            <a href="{{ route('admin.employees') }}"
class="block px-6 py-3 transition
{{ request()->routeIs('admin.employees')
    ? 'bg-teal-600 text-white'
    : 'hover:bg-teal-600' }}">
    ‎  ‎ Employees
</a>

           <a href="{{ route('admin.payroll') }}"
class="block px-6 py-3 transition
{{ request()->routeIs('admin.payroll')
    ? 'bg-teal-600 text-white'
    : 'hover:bg-teal-600' }}">
    ‎  ‎ Payroll
</a>

            @if(Auth::guard('admin')->user()->role != 'Assistant HR')

            <a href="{{ route('admin.payroll-history') }}"
class="block px-6 py-3 transition
{{ request()->routeIs('admin.payroll-history')
    ? 'bg-teal-600 text-white'
    : 'hover:bg-teal-600' }}">
    ‎  ‎ Payroll History
</a>

            <a href="{{ route('admin.activity-log') }}"
class="block px-6 py-3 transition
{{ request()->routeIs('admin.activity-log')
    ? 'bg-teal-600 text-white'
    : 'hover:bg-teal-600' }}">
    ‎  ‎ Activity Log
</a>

            @endif

            @if(Auth::guard('admin')->user()->role == 'Admin')

            <a href="{{ route('admin.add-admin') }}"
class="block px-6 py-3 transition
{{ request()->routeIs('admin.add-admin')
    ? 'bg-teal-600 text-white'
    : 'hover:bg-teal-600' }}">
    ‎  ‎ Add Admin User
</a>

            @endif

        </nav>

    </aside>

    <!-- Main Content -->
    <div class="flex-1">

        <!-- Navbar -->
        <header class="bg-white shadow px-8 py-5 flex justify-between items-center">

            <div>

                <div>

                        <h2 class="text-2xl font-bold text-slate-800">
                        Payroll Management System
                        </h2>

                        <p class="text-slate-500">
                        {{ now()->format('F d, Y') }}
                        </p>

                        </div>

            </div>

            <div class="text-right">

                <p class="font-semibold">

                    {{ Auth::guard('admin')->user()->name }}

                </p>

                <p class="text-sm text-slate-500">

                    {{ Auth::guard('admin')->user()->role }}

                </p>

                <form method="POST"
                      action="{{ route('logout') }}"
                      class="mt-2">

                    @csrf

                    <button
                        class="inline-flex items-center justify-center rounded-full border border-rose-500 px-3 py-2 text-rose-500 transition hover:bg-rose-50">

                        Logout

                    </button>

                </form>

            </div>

        </header>

        <main class="p-8">

            @yield('content')

        </main>

    </div>

</div>

</body>

<footer class="bg-gray-200 text-center text-black py-5">
    © 2026 NexaTech Payroll Management System
</footer>

</html>