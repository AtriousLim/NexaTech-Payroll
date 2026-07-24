@extends('layouts.admin')

@section('content')
<!--gap sa navbar-->
<div class="mx-auto max-w-6xl space-y-2 px-2 py-.1 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl p-4 shadow-lg border border-slate-200">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-2">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-sky-100 text-sky-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path d="M17 4H7a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3zm1 13a1 1 0 01-1 1H7a1 1 0 01-1-1V7a1 1 0 011-1h10a1 1 0 011 1v10z" />
                        <path d="M9 9h6M9 12h4M9 15h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Employee Management</h1>
                    <p class="text-slate-500 mt-1">Manage employee information and records.</p>
                </div>
            </div>

            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-900 px-4 py-2.5 text-white shadow-lg transition hover:bg-blue-800">
                <span class="text-lg">+</span>
                Add Employee
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-4 shadow-lg border border-slate-200">
        <div class="flex flex-col gap-2 xl:flex-row xl:items-center xl:justify-between">
            <form action="{{ route('admin.employees') }}" method="GET" class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search employee by name, code, position..."
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-700 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200"
                    autocomplete="off">
            </form>

            <div class="grid gap-1 sm:grid-cols-3 xl:w-2/5">
                <select name="department" class="rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-700 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200">
                    <option>All Departments</option>
                    <option>HR</option>
                    <option>Finance</option>
                    <option>Operations</option>
                </select>
                <select name="status" class="rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-700 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2.5 text-white shadow-lg transition hover:bg-slate-800">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-5 shadow-lg border border-slate-200">
        @if($employees->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold uppercase tracking-wide">Employee Code</th>
                            <th class="px-6 py-4 text-left font-semibold uppercase tracking-wide">Employee Name</th>
                            <th class="px-6 py-4 text-left font-semibold uppercase tracking-wide">Position</th>
                            <th class="px-6 py-4 text-left font-semibold uppercase tracking-wide">Department</th>
                            <th class="px-6 py-4 text-left font-semibold uppercase tracking-wide">Status</th>
                            <th class="px-6 py-4 text-left font-semibold uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($employees as $employee)
                            <tr>
                                <td class="px-6 py-4">{{ $employee->employee_code }}</td>
                                <td class="px-6 py-4">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td class="px-6 py-4">{{ $employee->position->position_title ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $employee->department ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @if(strtolower($employee->status) === 'active')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">Active</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-sm font-semibold text-rose-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.employees.view', ['employee' => $employee->id]) }}" class="text-slate-900 font-semibold hover:text-blue-700">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="grid place-items-center gap-6 py-16 text-center">
                <div class="inline-flex h-24 w-24 items-center justify-center rounded-full bg-slate-100 text-sky-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-10 w-10">
                        <path d="M8 7h8M8 11h8M9 15h6" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">No employees found</h2>
                    <p class="text-slate-500 mt-2">Get started by adding a new employee to the system.</p>
                </div>
                <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-900 px-6 py-3 text-white shadow-lg transition hover:bg-blue-800">
                    + Add Employee
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
