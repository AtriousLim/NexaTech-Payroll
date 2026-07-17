@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold text-slate-800 mb-8">
    Dashboard Overview
</h1>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-slate-500">Total Employees</p>
        <h2 class="text-4xl font-bold text-blue-900 mt-2">156</h2>
        <p class="text-emerald-500 mt-2">+8 this month</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-slate-500">Active Employees</p>
        <h2 class="text-4xl font-bold text-teal-600 mt-2">148</h2>
        <p class="text-emerald-500 mt-2">94.8% Active</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-slate-500">Payroll This Month</p>
        <h2 class="text-4xl font-bold text-blue-900 mt-2">₱1,250,000</h2>
        <p class="text-slate-500 mt-2">July Payroll</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-slate-500">Pending Payroll</p>
        <h2 class="text-4xl font-bold text-rose-500 mt-2">12</h2>
        <p class="text-rose-500 mt-2">Needs Approval</p>
    </div>

</div>

<div class="grid lg:grid-cols-2 gap-6 mt-8">

    <div class="bg-white rounded-2xl shadow">

        <div class="border-b p-5">
            <h2 class="font-bold text-xl">
                Recent Payroll Activity
            </h2>
        </div>

        <table class="w-full">

            <thead class="bg-slate-100">

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

        <div class="border-b p-5">

            <h2 class="font-bold text-xl">
                Recent System Activity
            </h2>

        </div>

        <div class="p-5 space-y-4">

            <div class="flex justify-between">

                <span>Admin logged in</span>

                <span class="text-slate-500">8:30 AM</span>

            </div>

            <div class="flex justify-between">

                <span>Payroll generated</span>

                <span class="text-slate-500">8:00 AM</span>

            </div>

            <div class="flex justify-between">

                <span>Employee record updated</span>

                <span class="text-slate-500">Yesterday</span>

            </div>

            <div class="flex justify-between">

                <span>Backup completed</span>

                <span class="text-slate-500">Yesterday</span>

            </div>

        </div>

    </div>

</div>

@endsection