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

    <a href="{{ route('admin.employees.create') }}"
        class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-3 rounded-xl shadow inline-block">

        + Add Employee

    </a>

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

        @forelse($employees as $emp)
        <tr class="border-b">

            <td class="p-4">{{ $emp->employee_code }}</td>

            <td class="p-4">{{ $emp->first_name }} {{ $emp->last_name }}</td>

            <td class="p-4">{{ $emp->position_title ?? 'Unassigned' }}</td>

            <td class="p-4">

                @if(strtolower($emp->status) === 'active')
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Active</span>
                @else
                    <span class="bg-rose-100 text-rose-600 px-3 py-1 rounded-full">Inactive</span>
                @endif

            </td>

            <td class="p-4">

                <a href="{{ route('admin.employees.view', ['employee' => $emp->id]) }}" class="text-blue-900 font-semibold">View</a>

            </td>

        </tr>
        @empty
        <tr>
            <td class="p-4" colspan="5">No employees found.</td>
        </tr>
        @endforelse

        </tbody>

    </table>

</div>

@endsection