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

        <form action="{{ url('/admin/employees') }}" method="GET">
    <input 
        type="text" 
        name="search"
        value="{{ request('search') }}"
        placeholder="Search employee..." 
        class="w-80 border rounded-xl px-4 py-2"
    >
</form>
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
        @forelse($employees as $employee)
        <tr class="border-b">
            <td class="p-4">{{ $employee->employee_code }}</td>
            <td class="p-4">{{ $employee->first_name }} {{ $employee->last_name }}</td>
            <td class="p-4">
                {{-- If you have a position relationship, use $employee->position->name. --}}
                {{-- Otherwise, this falls back to showing their raw position_id number. --}}
                {{ $employee->position->name ?? 'Position ID: ' . $employee->position_id }}
            </td>
            <td class="p-4">
                @if(strtolower($employee->status) === 'active')
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm">
                        Active
                    </span>
                @else
                    <span class="bg-rose-100 text-rose-600 px-3 py-1 rounded-full text-sm">
                        Inactive
                    </span>
                @endif
            </td>
            <td class="p-4">
                <button class="text-blue-900 font-semibold hover:underline">
                    View
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="p-8 text-center text-slate-500">
                No employees found matching "{{ request('search') }}".
            </td>
        </tr>
        @endforelse
        </tbody>

    </table>

</div>

@endsection