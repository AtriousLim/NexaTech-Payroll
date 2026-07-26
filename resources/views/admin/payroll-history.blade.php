@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Payroll History
            </h1>
            <p class="mt-1 text-slate-500">
                View all previously confirmed and processed payroll records.
            </p>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Gross Pay</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Deductions</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Net Pay</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Processed Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">
                                    {{ $payroll->employee->first_name ?? 'N/A' }} 
                                    {{ $payroll->employee->last_name ?? '' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $payroll->employee->employee_code ?? '' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $payroll->employee->department->department_name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                                ₱{{ number_format($payroll->gross_pay ?? 0, 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-rose-600 font-medium">
                                -₱{{ number_format(
                                    ($payroll->total_deductions ?? $payroll->total_deduction ?? 0) + 
                                    $payroll->items->where('item_type', 'deduction')->sum('amount'), 
                                    2 
                                ) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-600 font-bold">
                                ₱{{ number_format($payroll->net_pay ?? 0, 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($payroll->created_at)->format('M d, Y - h:i A') }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                    Processed
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                No payroll history found. Process a payroll to see records here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payrolls->hasPages())
            <div class="flex flex-col gap-4 border-t border-slate-200 bg-slate-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm font-medium text-slate-600">
                    Page {{ $payrolls->currentPage() }} of {{ $payrolls->lastPage() }}
                </div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white p-1 shadow-sm ring-1 ring-slate-200">
                    @if($payrolls->onFirstPage())
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-400">
                            ‹
                        </span>
                    @else
                        <a href="{{ $payrolls->previousPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-700 transition hover:bg-slate-100">
                            ‹
                        </a>
                    @endif

                    <span class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-full bg-slate-100 px-3 text-sm font-semibold text-slate-700">
                        {{ $payrolls->currentPage() }}
                    </span>

                    @if($payrolls->hasMorePages())
                        <a href="{{ $payrolls->nextPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-700 transition hover:bg-slate-100">
                            ›
                        </a>
                    @else
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-400">
                            ›
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>

@endsection