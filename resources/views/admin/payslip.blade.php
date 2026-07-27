<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->employee?->first_name }} {{ $payroll->employee?->last_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .payslip-container { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="payslip-container bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
            <div class="flex items-start justify-between border-b border-slate-200 pb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">NexaTech Payroll System</h1>
                    <p class="mt-1 text-slate-500">Professional Payslip</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-500">Payroll Number</p>
                    <p class="text-lg font-semibold text-slate-800">{{ $payroll->payrollEntry?->payroll_number ?? 'PR-' . $payroll->id }}</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <h2 class="text-lg font-semibold text-slate-800">Employee Information</h2>
                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-700">Name:</span> {{ $payroll->employee?->first_name }} {{ $payroll->employee?->last_name }}</p>
                        <p><span class="font-semibold text-slate-700">Employee Code:</span> {{ $payroll->employee?->employee_code }}</p>
                        <p><span class="font-semibold text-slate-700">Department:</span> {{ $payroll->employee?->department?->department_name }}</p>
                        <p><span class="font-semibold text-slate-700">Position:</span> {{ $payroll->employee?->position?->position_title }}</p>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <h2 class="text-lg font-semibold text-slate-800">Payroll Summary</h2>
                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-700">Pay Period:</span> {{ $payroll->cutoff_start }} to {{ $payroll->cutoff_end }}</p>
                        <p><span class="font-semibold text-slate-700">Prepared By:</span> {{ $payroll->processed_by ? 'Admin #' . $payroll->processed_by : 'N/A' }}</p>
                        <p><span class="font-semibold text-slate-700">Date Processed:</span> {{ $payroll->created_at->format('M d, Y h:i A') }}</p>
                        <p><span class="font-semibold text-slate-700">Status:</span> <span class="font-semibold {{ $payroll->status === 'Paid' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $payroll->status }}</span></p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <h3 class="text-lg font-semibold text-slate-800">Earnings</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Basic Salary</span><span>₱{{ number_format($payroll->payrollEntry?->basic_salary ?? 0, 2) }}</span></div>
                        <div class="flex justify-between"><span>Gross Salary</span><span>₱{{ number_format($payroll->gross_pay, 2) }}</span></div>
                        <div class="flex justify-between"><span>Overtime Pay</span><span>₱{{ number_format($payroll->payrollEntry?->overtime_pay ?? 0, 2) }}</span></div>
                        <div class="flex justify-between"><span>Bonuses</span><span>₱{{ number_format($payroll->payrollEntry?->total_bonus ?? 0, 2) }}</span></div>
                        <div class="flex justify-between"><span>Incentives</span><span>₱{{ number_format($payroll->payrollEntry?->total_incentive ?? 0, 2) }}</span></div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <h3 class="text-lg font-semibold text-slate-800">Deductions</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Deductions</span><span>₱{{ number_format($payroll->payrollEntry?->total_deduction ?? 0, 2) }}</span></div>
                        <div class="flex justify-between"><span>Late Deduction</span><span>₱{{ number_format($payroll->late_deductions, 2) }}</span></div>
                        <div class="flex justify-between"><span>SSS</span><span>₱{{ number_format($payroll->sss_deduction, 2) }}</span></div>
                        <div class="flex justify-between"><span>PhilHealth</span><span>₱{{ number_format($payroll->philhealth_deduction, 2) }}</span></div>
                        <div class="flex justify-between"><span>Pag-IBIG</span><span>₱{{ number_format($payroll->pagibig_deduction, 2) }}</span></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-xl bg-slate-900 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Net Salary</p>
                        <p class="text-3xl font-bold">₱{{ number_format($payroll->net_pay, 2) }}</p>
                    </div>
                    <button type="button" onclick="window.print()" class="no-print rounded-lg border border-slate-700 bg-white px-4 py-2 font-semibold text-slate-900 hover:bg-slate-100">Print Payslip</button>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-slate-800">Itemized Payroll Entries</h3>
                <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left">Type</th>
                                <th class="px-4 py-3 text-left">Name</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payroll->items as $item)
                                <tr class="border-t border-slate-200">
                                    <td class="px-4 py-3">{{ $item->item_type }}</td>
                                    <td class="px-4 py-3">{{ $item->item_name }}</td>
                                    <td class="px-4 py-3 text-right">₱{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-slate-500">No items recorded for this payroll.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="no-print mt-8 max-w-4xl mx-auto text-center">
        <a href="javascript:history.back()" class="inline-block rounded-lg bg-slate-600 px-4 py-2 font-semibold text-white hover:bg-slate-700">Back to Payroll History</a>
    </div>
</body>
</html>
