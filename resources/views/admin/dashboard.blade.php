@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold text-slate-800 mb-8">
    Dashboard Overview
</h1>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-blue-100 text-blue-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 448 512" fill="currentColor">
                <path d="M224 248a120 120 0 1 0 0-240 120 120 0 1 0 0 240zm-29.7 56C95.8 304 16 383.8 16 482.3 16 498.7 29.3 512 45.7 512l356.6 0c16.4 0 29.7-13.3 29.7-29.7 0-98.5-79.8-178.3-178.3-178.3l-59.4 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Total Employees</p>
            <h2 class="text-3xl font-bold text-blue-900 mt-2">156</h2>
            <p class="text-emerald-500 mt-2">+8 this month</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-emerald-100 text-emerald-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h4v-2.5c0-2.33-4.67-3.5-7-3.5z" />
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Active Employees</p>
            <h2 class="text-3xl font-bold text-teal-600 mt-2">148</h2>
            <p class="text-emerald-500 mt-2">94.8% Active</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-violet-100 text-violet-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 6H4a2 2 0 00-2 2v2h20V8a2 2 0 00-2-2zm0 6H2v6a2 2 0 002 2h16a2 2 0 002-2v-6zm-5 3h-2v2h2v-2zm-4 0h-2v2h2v-2z" />
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Payroll This Month</p>
            <h2 class="text-3xl font-bold text-blue-900 mt-2">₱1,250,000</h2>
            <p class="text-slate-500 mt-2">July Payroll</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-rose-100 text-rose-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Pending Payroll</p>
            <h2 class="text-3xl font-bold text-rose-500 mt-2">12</h2>
            <p class="text-rose-500 mt-2">Needs Approval</p>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mt-8">

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-blue-100 text-blue-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M4 7h16v2H4V7zm0 4h16v2H4v-2zm0 4h10v2H4v-2z" />
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Departments</p>
            <h2 class="text-3xl font-bold text-slate-900 mt-2">6</h2>
            <p class="text-slate-500 mt-2">Total Departments</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-emerald-100 text-emerald-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 512 512" fill="currentColor">
                <path d="M200 48l112 0c4.4 0 8 3.6 8 8l0 40-128 0 0-40c0-4.4 3.6-8 8-8zm-56 8l0 40-80 0C28.7 96 0 124.7 0 160l0 96 512 0 0-96c0-35.3-28.7-64-64-64l-80 0 0-40c0-30.9-25.1-56-56-56L200 0c-30.9 0-56 25.1-56 56zM512 304l-192 0 0 16c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32l0-16-192 0 0 112c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-112z"/>
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Positions</p>
            <h2 class="text-3xl font-bold text-slate-900 mt-2">18</h2>
            <p class="text-slate-500 mt-2">Total Positions</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-violet-100 text-violet-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Payroll Runs</p>
            <h2 class="text-3xl font-bold text-slate-900 mt-2">24</h2>
            <p class="text-slate-500 mt-2">This Year</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 flex items-start gap-4">
        <div class="bg-amber-100 text-amber-700 rounded-2xl p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-slate-500">Next Payroll</p>
            <h2 class="text-3xl font-bold text-slate-900 mt-2">Aug 1, 2026</h2>
            <p class="text-slate-500 mt-2">Upcoming Run</p>
        </div>
    </div>

</div>

<div class="grid lg:grid-cols-2 gap-6 mt-8">

    <div class="bg-white rounded-2xl shadow">

        <div class="bg-gray-200 border-b p-5 rounded-t-lg">
    <h2 class="font-bold text-xl">
        Recent Payroll Activity
    </h2>
</div>

        <table class="w-full">

            <thead class="bg-gray-100">

            <tr>

                <th class="text-left p-4">Employee</th>
                <th class="text-left p-4">Period</th>
                <th class="text-left p-4">Status</th>

            </tr>

            </thead>

            <tbody>

            <tr class="border-b">

                <td class="p-4">EMP-001</td>
                <td class="p-4">July 1-15</td>
                <td class="p-4 text-emerald-500 font-semibold">Paid</td>

            </tr>

            <tr class="border-b">

                <td class="p-4">EMP-002</td>
                <td class="p-4">July 1-15</td>
                <td class="p-4 text-rose-500 font-semibold">Pending</td>

            </tr>

            <tr>

                <td class="p-4">EMP-003</td>
                <td class="p-4">July 1-15</td>
                <td class="p-4 text-emerald-500 font-semibold">Paid</td>

            </tr>

            </tbody>

        </table>

    </div>

    <div class="bg-white rounded-2xl shadow">

        <div class="bg-gray-200 border-b p-5 rounded-t-lg">

            <h2 class="font-bold text-xl">
                Recent System Activity
            </h2>

        </div>

        <div class="p-5 space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start justify-between gap-4">
                    <span class="text-slate-800">{{ $activity->action }}</span>
                    <span class="shrink-0 text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-slate-500">No recent system activity.</p>
            @endforelse
        </div>

    </div>

</div>

@endsection
