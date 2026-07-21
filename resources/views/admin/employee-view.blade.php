@extends('layouts.admin')

@section('content')

<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Employee details</p>
            <h1 class="text-3xl font-semibold text-slate-900">Employee Overview</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-500">A polished employee dashboard with status, key metrics, and attendance history.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 justify-end w-full md:w-auto">
            <form method="POST" action="{{ route('admin.employees.destroy', ['employee' => $employeeDetail->id]) }}" onsubmit="return confirm('Delete this employee? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 shadow-sm transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 focus:ring-offset-white">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Delete employee
                </button>
            </form>
            <a href="{{ route('admin.employees') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white" aria-label="Exit to employee list">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                    <path d="M12 4L6 10L12 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Exit
            </a>
        </div>
    </div>
    <div class="flex items-center gap-5">
        <div class="flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-slate-900 text-xl font-semibold text-white">
                        {{ strtoupper(substr($employeeDetail->first_name, 0, 1)) }}{{ strtoupper(substr($employeeDetail->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Employee</p>
                        <h2 class="text-3xl font-semibold text-slate-900">{{ $employeeDetail->first_name }} {{ $employeeDetail->last_name }}</h2>
                        <p class="mt-2 text-sm text-slate-500">{{ $employeeDetail->employee_code }} · {{ $employeeDetail->position_title ?? 'Unassigned' }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">{{ ucfirst($employeeDetail->status) }}</span>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">{{ ucfirst($employeeDetail->role ?? 'Employee') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Attendance</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $attendance->count() }}</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Late</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $attendance->sum('late_minutes') }}m</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Overtime</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $attendance->sum('overtime_minutes') }}m</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Status</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ ucfirst($employeeDetail->status) }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Quick details</h2>
                <div class="mt-5 grid gap-4 text-sm text-slate-700 sm:grid-cols-2">
                    <div class="rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-slate-500">Contact Number</dt>
                        <dd class="mt-2 font-medium text-slate-900">{{ $employeeDetail->contact_number }}</dd>
                    </div>
                    <div class="rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-slate-500">Email</dt>
                        <dd class="mt-2 font-medium text-slate-900">{{ $employeeDetail->gmail }}</dd>
                    </div>
                    <div class="rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-slate-500">Address</dt>
                        <dd class="mt-2 font-medium text-slate-900">{{ $employeeDetail->address }}</dd>
                    </div>
                    <div class="rounded-3xl bg-white p-4 shadow-sm">
                        <dt class="text-slate-500">Role</dt>
                        <dd class="mt-2 font-medium text-slate-900">{{ ucfirst($employeeDetail->role ?? 'Employee') }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Attendance history</h2>
                    <p class="text-sm text-slate-500">Recent clock in and clock out records.</p>
                </div>
                <div class="inline-flex items-center rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">
                    {{ $attendance->count() }} records
                </div>
            </div>

            @if($attendance->isEmpty())
                <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-slate-500">
                    No attendance records found for this employee.
                </div>
            @else
                <div class="overflow-x-auto rounded-[1.5rem] border border-slate-200">
                    <table class="min-w-full text-left divide-y divide-slate-200">
                        <thead class="bg-slate-100 text-slate-600 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Time In</th>
                                <th class="px-4 py-3">Time Out</th>
                                <th class="px-4 py-3">Late</th>
                                <th class="px-4 py-3">Overtime</th>     
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($attendance as $record)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ date('M d, Y', strtotime($record->date)) }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ $record->time_in ?? '—' }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ $record->time_out ?? '—' }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ $record->late_minutes }}  min</td>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ $record->overtime_minutes }}  min</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
