@extends('layouts.admin')

@section('content')
@if(session('success'))
    <div id="success-notification" role="status" class="fixed right-5 top-5 z-50 flex max-w-sm items-start gap-3 rounded-2xl border border-emerald-200 bg-white p-4 text-emerald-800 shadow-xl transition duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" aria-hidden="true">
            <path d="m5 12 4 4L19 6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <div class="flex-1">
            <p class="font-semibold">Success</p>
            <p class="mt-1 text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
        <button type="button" data-dismiss-notification aria-label="Dismiss notification" class="rounded-lg p-1 text-emerald-700 transition hover:bg-emerald-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                <path d="M6 6l12 12M18 6 6 18" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notification = document.getElementById('success-notification');
            const dismissButton = notification?.querySelector('[data-dismiss-notification]');

            const dismiss = function () {
                notification.classList.add('translate-x-full', 'opacity-0');
                window.setTimeout(function () {
                    notification.remove();
                }, 300);
            };

            dismissButton?.addEventListener('click', dismiss);
            window.setTimeout(dismiss, 4000);
        });
    </script>
@endif

<div class="bg-white rounded-2xl shadow p-6 ">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Employee Management</h1>
            <p class="text-slate-500 mt-1">Manage employee information and records.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.employees.export.xml', request()->query()) }}" class="inline-flex items-center gap-2 rounded-2xl border border-blue-900 bg-white px-4 py-2.5 font-semibold text-blue-900 shadow-sm transition hover:bg-blue-50">
                Export XML
            </a>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-900 px-4 py-2.5 text-white shadow-lg transition hover:bg-blue-800">
                <span class="text-lg">+</span>
                Add Employee
            </a>
        </div>
    </div>
                <!--search buttons-->
        <form action="{{ route('admin.employees') }}" method="GET" class="grid grid-cols-1 gap-3 mt-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)_minmax(0,1fr)_auto]">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search employee by name, code, position..."
                class="w-full border rounded-lg border border-black-200 bg-white px-4 py-2 text-medium text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0"
                autocomplete="off">

            <select name="department" class="rounded-lg border border-black-200 bg-white px-4 py-3 text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected((string) request('department') === (string) $department->id)>
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="rounded-lg border border-black-200 bg-white px-4 py-3 text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0">
                <option value="">All Status</option>
                <option value="Active" @selected(request('status') == 'Active')>Active</option>
                <option value="Inactive" @selected(request('status') == 'Inactive')>Inactive</option>
            </select>

            <button type="submit" class="rounded-lg bg-blue-900 px-6 py-3 text-white transition hover:bg-blue-800">
                Filter
            </button>
        </form>

        

        <div class="overflow-x-auto mt-4 border border-slate-200 rounded-xl bg-white shadow-sm">
            @if($employees->count())
                <table class="min-w-full divide-y divide-slate-200 text-sm text-black-700">
                    <thead class="bg-slate-50 text-black-600">
                        <tr> <!-- Table headers for employee management -->
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wide bg-gray-200">Employee Code</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wide bg-gray-200">Employee Name</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wide bg-gray-200">Position</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wide bg-gray-200">Department</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wide bg-gray-200">Status</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wide bg-gray-200">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black-200 bg-white">
                        @foreach($employees as $employee)
                            <tr>
                                <td class="px-6 py-4 ">{{ $employee->employee_code }}</td>
                                <td class="px-6 py-4">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td class="px-6 py-4">{{ $employee->position->position_title ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $employee->position->department->department_name ?? 'N/A' }}</td>
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

                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                   <div class="text-sm text-slate-600">
                Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} employees
            </div>
                    <div class="inline-flex overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-slate-200">
                        <a href="{{ $employees->previousPageUrl() ?? '#' }}"
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 disabled:pointer-events-none disabled:opacity-50 {{ $employees->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}"
                           aria-label="Previous page">
                            ‹
                        </a>
                        <span class="inline-flex items-center justify-center px-6 py-2 text-sm font-semibold text-slate-900 bg-blue-100">
                            {{ $employees->currentPage() }}
                        </span>
                        <a href="{{ $employees->nextPageUrl() ?? '#' }}"
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 {{ $employees->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}"
                           aria-label="Next page">
                            ›
                        </a>
                    </div>
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
                    <a href="{{ route('admin.employees.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2 rounded-lg">
                        + Add Employee
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
