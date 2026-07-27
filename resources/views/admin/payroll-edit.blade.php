@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-3xl font-bold text-slate-800">Edit Payroll</h1>
        <p class="mt-1 text-slate-500">Adjust the selected bonuses, incentives, deductions, and payroll amounts before approval.</p>
    </div>

    <form method="POST" action="{{ route('admin.payroll.update', $payroll) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="text-xl font-semibold text-slate-800">Employee Details</h2>
                <div class="mt-4 space-y-2 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-700">Name:</span> {{ $employee->first_name }} {{ $employee->last_name }}</p>
                    <p><span class="font-semibold text-slate-700">Employee Code:</span> {{ $employee->employee_code }}</p>
                    <p><span class="font-semibold text-slate-700">Department:</span> {{ $employee->department?->department_name }}</p>
                    <p><span class="font-semibold text-slate-700">Position:</span> {{ $employee->position?->position_title }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="text-xl font-semibold text-slate-800">Payroll Values</h2>
                <div class="mt-4 grid gap-4">
                    <label class="text-sm font-medium text-slate-700">
                        Gross Pay
                        <input type="number" step="0.01" name="gross_pay" value="{{ old('gross_pay', $payroll->gross_pay) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Overtime Pay
                        <input type="number" step="0.01" name="overtime_pay" value="{{ old('overtime_pay', $payroll->payrollEntry?->overtime_pay ?? 0) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Late Deductions
                        <input type="number" step="0.01" name="late_deductions" value="{{ old('late_deductions', $payroll->late_deductions) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        SSS Deduction
                        <input type="number" step="0.01" name="sss_deduction" value="{{ old('sss_deduction', $payroll->sss_deduction) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        PhilHealth Deduction
                        <input type="number" step="0.01" name="philhealth_deduction" value="{{ old('philhealth_deduction', $payroll->philhealth_deduction) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Pag-IBIG Deduction
                        <input type="number" step="0.01" name="pagibig_deduction" value="{{ old('pagibig_deduction', $payroll->pagibig_deduction) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-800">Adjust Items</h2>
            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                <div>
                    <h3 class="font-semibold text-slate-700">Bonuses</h3>
                    @foreach($bonuses as $bonus)
                        <label class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="bonuses[]" value="{{ $bonus->id }}" @checked(in_array($bonus->id, $selectedBonuses))>
                            {{ $bonus->bonus_name }} (+₱{{ number_format($bonus->bonus_amount, 2) }})
                        </label>
                    @endforeach
                </div>
                <div>
                    <h3 class="font-semibold text-slate-700">Incentives</h3>
                    @foreach($incentives as $incentive)
                        <label class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="incentives[]" value="{{ $incentive->id }}" @checked(in_array($incentive->id, $selectedIncentives))>
                            {{ $incentive->incentive_name }} (+₱{{ number_format($incentive->incentive_amount, 2) }})
                        </label>
                    @endforeach
                </div>
                <div>
                    <h3 class="font-semibold text-slate-700">Deductions</h3>
                    @foreach($deductions as $deduction)
                        <label class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="deductions[]" value="{{ $deduction->id }}" @checked(in_array($deduction->id, $selectedDeductions))>
                            {{ $deduction->deduction_name }} (-₱{{ number_format($deduction->deduction_amount, 2) }})
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.payroll-history') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Cancel</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white">Save Changes</button>
        </div>
    </form>
</div>
@endsection
