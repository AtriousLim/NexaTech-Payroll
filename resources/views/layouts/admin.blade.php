<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaTech Payroll</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-sky-50">

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
                class="block px-6 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-lg">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path d="M10 2.5L2 8v8a1 1 0 001 1h4a1 1 0 001-1V12h2v4a1 1 0 001 1h4a1 1 0 001-1V8l-8-5.5z" />
                    </svg>
                    <span>Overview</span>
                </span>
            </a>

            <a href="{{ route('admin.employees') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.employees') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-lg">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M10 4.5a3 3 0 100 6 3 3 0 000-6zM3.5 13a6.5 6.5 0 0113 0v1.25a1.25 1.25 0 01-1.25 1.25H4.75A1.25 1.25 0 013.5 14.25V13z" clip-rule="evenodd" />
                    </svg>
                    <span>Employees</span>
                </span>
            </a>

            <a href="{{ route('admin.payroll') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.payroll') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-lg">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path d="M10 2a7 7 0 100 14 7 7 0 000-14zm1 10.93V14a1 1 0 11-2 0v-1.07a4.01 4.01 0 01-2.47-1.81 1 1 0 111.74-.98A2.01 2.01 0 0010 11a2 2 0 002-2 1 1 0 112 0 4 4 0 01-4 4 4 4 0 01-1.27-.22A2.99 2.99 0 0111 12.93z" />
                    </svg>
                    <span>Payroll</span>
                </span>
            </a>

            @if(Auth::guard('admin')->user()->role != 'Assistant HR')

            <a href="{{ route('admin.payroll-history') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.payroll-history') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-lg">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm8 3V3h-2v2H8V3H6v2H4v2h12V5h-2z" clip-rule="evenodd" />
                    </svg>
                    <span>Payroll History</span>
                </span>
            </a>

            <a href="{{ route('admin.activity-log') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.activity-log') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-lg">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path d="M6 3a1 1 0 00-1 1v12a1 1 0 001 1h8a1 1 0 001-1V4a1 1 0 00-1-1H6zm2 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1zm0 4a1 1 0 011-1h4a1 1 0 110 2H9a1 1 0 01-1-1zm0 4a1 1 0 011-1h4a1 1 0 110 2H9a1 1 0 01-1-1z" />
                    </svg>
                    <span>Activity Log</span>
                </span>
            </a>

            @endif

            @if(Auth::guard('admin')->user()->role == 'Admin')

            <a href="{{ route('admin.add-admin') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.add-admin') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-lg">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M10 5a3 3 0 100 6 3 3 0 000-6zm4 7a4 4 0 10-8 0v1H5a1 1 0 100 2h10a1 1 0 100-2h-1v-1zM10 3a7 7 0 00-7 7v3a1 1 0 001 1h1a1 1 0 100-2H4v-1a5 5 0 0110 0v1h-1a1 1 0 100 2h1a1 1 0 001-1v-3a7 7 0 00-7-7z" clip-rule="evenodd" />
                        <path d="M11 10a1 1 0 10-2 0v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1v-1z" />
                    </svg>
                    <span>Add Admin User</span>
                </span>
            </a>

            @endif

        </nav>

    </aside>

    <!-- Main Content -->
    <div class="flex-1">

        <!-- Navbar -->
        <header class="bg-white shadow px-8 py-5 flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">

            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6 text-slate-700">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Payroll Management</h2>
                    <p class="text-sm text-slate-500">{{ now()->format('F d, Y') }} · Friday</p>
                </div>
            </div>

            <div class="text-right relative"> <!--user dropdown-->
                <details class="inline-block text-left relative">
                    <summary class="inline-flex items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 cursor-pointer hover:bg-slate-100" style="list-style: none;">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-900 text-sm font-semibold text-white">
                            @php
                                $adminName = Auth::guard('admin')->user()->name;
                                $initials = collect(explode(' ', trim($adminName)))
                                    ->filter()
                                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                    ->take(2)
                                    ->join('');
                            @endphp
                            {{ $initials ?: 'AD' }}
                        </span>
                        <div class="min-w-0 text-left">
                            <p class="font-semibold text-slate-800 truncate">{{ $adminName }}</p>
                            <p class="text-sm text-slate-500 truncate">{{ Auth::guard('admin')->user()->role }}</p>
                        </div>
                        <span class="text-slate-400">▾</span>
                    </summary>

                    <div class="absolute right-0 mt-2 w-44 rounded-xl border border-slate-200 bg-white p-2 shadow-lg"><!-- dropdown menu -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg bg-rose-600 px-4 py-2 text-white transition hover:bg-rose-800">
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
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