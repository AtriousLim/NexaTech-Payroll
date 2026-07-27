@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    Payroll Preview
                </h1>

                <p class="text-slate-500 mt-1">
                    Review payroll before processing
                </p>

            </div>

            <a href="{{ route('admin.payroll') }}"
               class="px-5 py-2 rounded-lg border border-slate-300 hover:bg-slate-100 transition">

                ← Back

            </a>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">

                    {{ $employee->first_name ?? '' }}
                    {{ $employee->last_name ?? '' }}

                </h2>

                <p class="text-slate-500 mt-1">

                    {{ $employee->position?->position_title ?? 'N/A' }}

                    •

                    {{ $employee->department?->department_name ?? 'N/A' }}

                </p>

            </div>

            <div class="text-right">

                <p class="text-sm text-slate-500">

                    Employee Code

                </p>

                <p class="font-semibold text-slate-700">

                    {{ $employee->employee_code ?? 'N/A' }}

                </p>

            </div>

        </div>

    </div>

    <hr class="my-6">

    <form method="POST" action="{{ route('admin.payroll.process', $employee) }}" id="payroll-preview-form">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    <div class="bg-white border border-slate-200 rounded-xl p-5">

        <p class="text-sm text-slate-500"><strong>Department</strong></p>

        <h3 class="text-xl font-semibold text-slate-800 mt-1">

            {{ $employee->department?->department_name ?? 'N/A' }}

        </h3>

    </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Position</strong></p>

            <h3 class="text-xl font-semibold text-slate-800 mt-1">

                {{ $employee->position?->position_title ?? 'N/A' }}

            </h3>

        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Present Days</strong></p>

            <h3 class="text-xl font-semibold text-slate-800 mt-1">

                {{ $presentDays }}

            </h3>

        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Late Minutes</strong></p>

            <h3 class="text-xl font-semibold text-slate-800 mt-1">

                {{ $lateMinutes }}

            </h3>

        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Overtime Minutes</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                {{ $overtimeMinutes }}
            </h3>

        </div>

         <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Monthly Salary</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                ₱{{ number_format($monthlySalary,2) }}
            </h3>

        </div>

         <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Gross Pay</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                ₱{{ number_format($grossPay,2) }}
            </h3>

        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Overtime Pay</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                ₱{{ number_format($overtimePay,2) }}
            </h3>

        </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5">

        <p class="text-sm text-slate-500"><strong>Bonuses</strong></p>

        @forelse($bonuses as $bonus)

            <label class="flex items-center gap-2 mt-2">

                <input
                    type="checkbox"
                    class="bonus-checkbox"
                    data-amount="{{ $bonus->bonus_amount }}"
                    name="bonuses[]"
                    value="{{ $bonus->id }}">

                {{ $bonus->bonus_name }}

                (+₱{{ number_format($bonus->bonus_amount,2) }})

            </label>

        @empty

            <p class="text-slate-400">
                No bonuses available.
            </p>

        @endforelse

    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5">

        <p class="text-sm text-slate-500"><strong>Incentives</strong></p>

        @forelse($incentives as $incentive)

            <label class="flex items-center gap-2 mt-2">

             <input
                    type="checkbox"
                    class="incentive-checkbox"
                    data-amount="{{ $incentive->incentive_amount }}"
                    name="incentives[]"
                    value="{{ $incentive->id }}">

                    {{ $incentive->incentive_name }}

                    (+₱{{ number_format($incentive->incentive_amount,2) }})

            </label>

        @empty

            <p class="text-slate-400">
                No incentives available.
            </p>

        @endforelse

    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5">

        <p class="text-sm text-slate-500"><strong>Deductions</strong></p>

        @forelse($deductions as $deduction)

            <label class="flex items-center gap-2 mt-2">

               <input
                    type="checkbox"
                    class="deduction-checkbox"
                    data-amount="{{ $deduction->deduction_amount }}"
                    name="deductions[]"
                    value="{{ $deduction->id }}">
                {{ $deduction->deduction_name }}

                (-₱{{ number_format($deduction->deduction_amount,2) }})

            </label>

        @empty

            <p class="text-slate-400">
                No deductions available.
            </p>

        @endforelse

    </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Late Deduction</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                ₱{{ number_format($lateDeduction,2) }}
            </h3>

        </div>

       <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>SSS</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                ₱{{ number_format($sss,2) }}
            </h3>

        </div>

       <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>PhilHealth</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                ₱{{ number_format($philhealth,2) }}
            </h3>

        </div>

       <div class="bg-white border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500"><strong>Pag-IBIG</strong></p>
            <h3 class="text-xl font-semibold text-slate-800 mt-1">
                ₱{{ number_format($pagibig,2) }}
            </h3>

        </div>

    </div>

    <div class="mt-5">

    <h2 class="text-xl font-bold text-blue-900 mb-4">

        Attendance Summary

    </h2>

        <table class="min-w-full border">

            <thead class="bg-slate-100">

            <tr>

                <th class="p-3 text-left">Date</th>

                <th class="p-3 text-left">Time In</th>

                <th class="p-3 text-left">Time Out</th>

                <th class="p-3 text-left">Status</th>

                <th class="p-3 text-left">Late</th>

                <th class="p-3 text-left">OT</th>

            </tr>

            </thead>

            <tbody>

            @foreach($attendance as $record)

            <tr class="border-b">

                <td class="px-3 py-2">
                    {{ $record->attendance_date }}
                </td>

                <td class="px-3 py-2">
                    {{ $record->time_in }}
                </td>

                <td class="px-3 py-2">
                    {{ $record->time_out }}
                </td>

                <td class="px-3 py-2">
                    {{ $record->status }}
                </td>

                <td class="px-3 py-2">
                    {{ $record->late_minutes }}
                </td>

                <td class="px-3 py-2">
                    {{ $record->overtime_minutes }}
                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

        <div class="mt-6 text-center">

    <h2 class="text-green-600 font-bold text-xl">
    NET PAY
    </h2>

    <p id="netPay"
    class="text-4xl font-bold mt-1">
    ₱0.00
    </p>

    <button
    type="submit"
    class="mt-4 bg-blue-700 hover:bg-blue-800 transition px-8 py-3 rounded-xl text-white font-semibold cursor-pointer">
    Confirm Payroll
    </button>

    </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let gross = Number('{{ $grossPay ?? 0 }}');
let overtime = Number('{{ $overtimePay ?? 0 }}');
let late = Number('{{ $lateDeduction ?? 0 }}');
let sss = Number('{{ $sss ?? 0 }}');
let philhealth = Number('{{ $philhealth ?? 0 }}');
let pagibig = Number('{{ $pagibig ?? 0 }}');

function updatePayroll() {
    let bonusTotal = 0;

    document.querySelectorAll(".bonus-checkbox:checked")
    .forEach(item => {
        bonusTotal += Number(item.dataset.amount);
    });

    let incentiveTotal = 0;

    document.querySelectorAll(".incentive-checkbox:checked")
    .forEach(item => {
        incentiveTotal += Number(item.dataset.amount);
    });

    let deductionTotal = 0;

    document.querySelectorAll(".deduction-checkbox:checked")
    .forEach(item => {
        deductionTotal += Number(item.dataset.amount);
    });

    let net =
        gross
        +
        overtime
        +
        bonusTotal
        +
        incentiveTotal
        -
        late
        -
        deductionTotal
        -
        sss
        -
        philhealth
        -
        pagibig;

    document.getElementById("netPay").innerHTML =
        '₱' + net.toLocaleString(
            undefined,
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
}

document
.querySelectorAll(
".bonus-checkbox,.incentive-checkbox,.deduction-checkbox"
)
.forEach(box => {
    box.addEventListener(
        "change",
        updatePayroll
    );
});

updatePayroll();

const form = document.getElementById('payroll-preview-form');
form?.addEventListener('submit', function (event) {
    event.preventDefault();
    Swal.fire({
        title: 'Confirm payroll processing?',
        text: 'A payslip will be generated and the payroll will be saved. Continue?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, process payroll',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>

@endsection