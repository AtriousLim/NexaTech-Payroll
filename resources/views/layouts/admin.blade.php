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

        <nav class="mt-8 space-y-3"> <!--gap sa navigation items-->

            <a href="{{ route('admin.dashboard') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-small">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path d="M10 2.5L2 8v8a1 1 0 001 1h4a1 1 0 001-1V12h2v4a1 1 0 001 1h4a1 1 0 001-1V8l-8-5.5z" />
                    </svg>
                    <span>Overview</span>
                </span>
            </a>

            <a href="{{ route('admin.employees') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.employees') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-small">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor" class="h-5 w-5">
                        <path d="M320 64C355.3 64 384 92.7 384 128C384 163.3 355.3 192 320 192C284.7 192 256 163.3 256 128C256 92.7 284.7 64 320 64zM416 376C416 401 403.3 423 384 435.9L384 528C384 554.5 362.5 576 336 576L304 576C277.5 576 256 554.5 256 528L256 435.9C236.7 423 224 401 224 376L224 336C224 283 267 240 320 240C373 240 416 283 416 336L416 376zM160 96C190.9 96 216 121.1 216 152C216 182.9 190.9 208 160 208C129.1 208 104 182.9 104 152C104 121.1 129.1 96 160 96zM176 336L176 368C176 400.5 188.1 430.1 208 452.7L208 528C208 529.2 208 530.5 208.1 531.7C199.6 539.3 188.4 544 176 544L144 544C117.5 544 96 522.5 96 496L96 439.4C76.9 428.4 64 407.7 64 384L64 352C64 299 107 256 160 256C172.7 256 184.8 258.5 195.9 262.9C183.3 284.3 176 309.3 176 336zM432 528L432 452.7C451.9 430.2 464 400.5 464 368L464 336C464 309.3 456.7 284.4 444.1 262.9C455.2 258.4 467.3 256 480 256C533 256 576 299 576 352L576 384C576 407.7 563.1 428.4 544 439.4L544 496C544 522.5 522.5 544 496 544L464 544C451.7 544 440.4 539.4 431.9 531.7C431.9 530.5 432 529.2 432 528zM480 96C510.9 96 536 121.1 536 152C536 182.9 510.9 208 480 208C449.1 208 424 182.9 424 152C424 121.1 449.1 96 480 96z"/>
                        <path fill-rule="evenodd" d="M10 4.5a3 3 0 100 6 3 3 0 000-6zM3.5 13a6.5 6.5 0 0113 0v1.25a1.25 1.25 0 01-1.25 1.25H4.75A1.25 1.25 0 013.5 14.25V13z" clip-rule="evenodd" />
                    </svg>
                    <span>Employees</span>
                </span>
            </a>

            <a href="{{ route('admin.payroll') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.payroll') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-small">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor" class="h-5 w-5">
                        <path d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z"/>
                        <path d="M10 2a7 7 0 100 14 7 7 0 000-14zm1 10.93V14a1 1 0 11-2 0v-1.07a4.01 4.01 0 01-2.47-1.81 1 1 0 111.74-.98A2.01 2.01 0 0010 11a2 2 0 002-2 1 1 0 112 0 4 4 0 01-4 4 4 4 0 01-1.27-.22A2.99 2.99 0 0111 12.93z" />
                    </svg>
                    <span>Payroll</span>
                </span>
            </a>

            @if(Auth::guard('admin')->user()->role != 'Assistant HR')

            <a href="{{ route('admin.payroll-history') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.payroll-history') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-small">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor" class="h-5 w-5">
                        <path d="M320 128C263.2 128 212.1 152.7 176.9 192L224 192C241.7 192 256 206.3 256 224C256 241.7 241.7 256 224 256L96 256C78.3 256 64 241.7 64 224L64 96C64 78.3 78.3 64 96 64C113.7 64 128 78.3 128 96L128 150.7C174.9 97.6 243.5 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C233 576 156.1 532.6 109.9 466.3C99.8 451.8 103.3 431.9 117.8 421.7C132.3 411.5 152.2 415.1 162.4 429.6C197.2 479.4 254.8 511.9 320 511.9C426 511.9 512 425.9 512 319.9C512 213.9 426 128 320 128z"/>
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm8 3V3h-2v2H8V3H6v2H4v2h12V5h-2z" clip-rule="evenodd" />
                    </svg>
                    <span>Payroll History</span>
                </span>
            </a>

            <a href="{{ route('admin.activity-log') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.activity-log') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-small">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor" class="h-5 w-5">
                        <path d="M64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-277.5c0-17-6.7-33.3-18.7-45.3L258.7 18.7C246.7 6.7 230.5 0 213.5 0L64 0zM325.5 176L232 176c-13.3 0-24-10.7-24-24L208 58.5 325.5 176z"/>
                    </svg>
                    <span>Activity Log</span>
                </span>
            </a>

            @endif

            @if(Auth::guard('admin')->user()->role == 'Admin')

            <a href="{{ route('admin.add-admin') }}"
                class="block px-6 py-3 transition {{ request()->routeIs('admin.add-admin') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 text-slate-100' }} rounded-small">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="currentColor" class="h-5 w-5">
                        <path d="M285.7 304c98.5 0 178.3 79.8 178.3 178.3 0 16.4-13.3 29.7-29.7 29.7L77.7 512C61.3 512 48 498.7 48 482.3 48 383.8 127.8 304 226.3 304l59.4 0zM528 80c13.3 0 24 10.7 24 24l0 48 48 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-48 0 0 48c0 13.3-10.7 24-24 24s-24-10.7-24-24l0-48-48 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l48 0 0-48c0-13.3 10.7-24 24-24zM256 248a120 120 0 1 1 0-240 120 120 0 1 1 0 240z"/>
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
                    <p class="text-sm text-slate-500">{{ now()->format('F d, Y') }} · {{ now()->format('l') }}</p>
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