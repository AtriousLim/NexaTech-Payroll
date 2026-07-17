@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Employee Management
        </h1>

        <p class="text-slate-500 mt-1">
            Manage employee information and records.
        </p>

    </div>

    <button
        class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-3 rounded-xl shadow">

        + Add Employee

    </button>

</div>

<div class="bg-white rounded-2xl shadow">

    <div class="p-5 border-b">

        <input
            type="text"
            placeholder="Search employee..."
            class="w-80 border rounded-xl px-4 py-2">

    </div>

    <table class="w-full">

        <thead class="bg-slate-100">

        <tr>

            <th class="p-4 text-left">Employee Code</th>

            <th class="p-4 text-left">Employee Name</th>

            <th class="p-4 text-left">Position</th>

            <th class="p-4 text-left">Status</th>

            <th class="p-4 text-left">Action</th>

        </tr>

        </thead>

        <tbody>

        <tr class="border-b">

            <td class="p-4">EMP-001</td>

            <td class="p-4">Juan Dela Cruz</td>

            <td class="p-4">Software Developer</td>

            <td class="p-4">

                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">

                    Active

                </span>

            </td>

            <td class="p-4">

                <button class="text-blue-900 font-semibold">

                    View

                </button>

            </td>

        </tr>

        <tr class="border-b">

            <td class="p-4">EMP-002</td>

            <td class="p-4">Maria Santos</td>

            <td class="p-4">HR Assistant</td>

            <td class="p-4">

                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">

                    Active

                </span>

            </td>

            <td class="p-4">

                <button class="text-blue-900 font-semibold">

                    View

                </button>

            </td>

        </tr>

        <tr>

            <td class="p-4">EMP-003</td>

            <td class="p-4">Pedro Reyes</td>

            <td class="p-4">Accountant</td>

            <td class="p-4">

                <span class="bg-rose-100 text-rose-600 px-3 py-1 rounded-full">

                    Inactive

                </span>

            </td>

            <td class="p-4">

                <button class="text-blue-900 font-semibold">

                    View

                </button>

            </td>

        </tr>

        </tbody>

    </table>

</div>

@endsection