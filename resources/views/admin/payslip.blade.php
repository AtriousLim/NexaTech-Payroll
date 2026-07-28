<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->employee?->first_name }} {{ $payroll->employee?->last_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Remove browser print header/footer (URL, date, page number) */
        @page {
            margin: 0;
            size: auto;
        }

        @media print {
            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .payslip-container {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 24px 32px !important;
                page-break-after: avoid;
            }

            .print-break-inside {
                page-break-inside: avoid;
            }

            thead th {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .net-salary-bar {
                background-color: #1e293b !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .info-card {
                border: 1px solid #e2e8f0 !important;
                border-radius: 4px !important;
            }
        }

        .info-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .info-table th {
            background-color: #f8fafc;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .info-table td {
            border-bottom: 1px solid #f1f5f9;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .amount-column {
            font-family: 'JetBrains Mono', 'Fira Code', monospace, 'Courier New';
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-slate-50 py-4">
    <div class="max-w-4xl mx-auto px-3">
        <!-- Payslip Container -->
        <div class="payslip-container bg-white border border-slate-200 shadow-sm rounded-xl p-5 md:p-6 print-break-inside">

            <!-- Document Header -->
            <div class="flex items-start justify-between border-b border-slate-200 pb-3 mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">NexaTech</h1>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium uppercase tracking-wider">Official Payslip</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Document No.</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $payroll->payrollEntry?->payroll_number ?? 'PR-' . str_pad((string)$payroll->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-[10px] text-slate-400 mt-1.5 uppercase tracking-wider">Date Issued</p>
                    <p class="text-xs font-medium text-slate-700">{{ $payroll->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Employee & Payroll Summary Grid -->
            <div class="grid gap-4 md:grid-cols-2 print-break-inside">
                <!-- Employee Information -->
                <div class="info-card p-3.5 bg-white">
                    <h2 class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2.5">Employee Information</h2>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Full Name</span>
                            <span class="text-xs font-semibold text-slate-800 text-right">{{ $payroll->employee?->first_name }} {{ $payroll->employee?->last_name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Employee Code</span>
                            <span class="text-xs font-semibold text-slate-800">{{ $payroll->employee?->employee_code }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Department</span>
                            <span class="text-xs font-semibold text-slate-800 text-right">{{ $payroll->employee?->department?->department_name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Position</span>
                            <span class="text-xs font-semibold text-slate-800 text-right">{{ $payroll->employee?->position?->position_title }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payroll Summary -->
                <div class="info-card p-3.5 bg-white">
                    <h2 class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2.5">Payroll Summary</h2>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Pay Period</span>
                            <span class="text-xs font-semibold text-slate-800 text-right">{{ \Carbon\Carbon::parse($payroll->cutoff_start)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($payroll->cutoff_end)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Prepared By</span>
                            <span class="text-xs font-semibold text-slate-800">{{ $payroll->processed_by ? 'Admin #' . $payroll->processed_by : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Processed On</span>
                            <span class="text-xs font-semibold text-slate-800">{{ $payroll->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Status</span>
                            <span class="text-xs font-semibold {{ $payroll->status === 'Paid' ? 'text-emerald-700' : 'text-amber-700' }} px-2 py-0.5 rounded-full {{ $payroll->status === 'Paid' ? 'bg-emerald-50' : 'bg-amber-50' }}">{{ $payroll->status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings & Deductions Grid -->
            <div class="grid gap-4 md:grid-cols-2 mt-4 print-break-inside">
                <!-- Earnings -->
                <div class="info-card p-3.5 bg-white">
                    <h2 class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2.5">Earnings</h2>
                    <table class="w-full info-table">
                        <thead>
                            <tr>
                                <th class="py-1.5 text-left text-xs">Description</th>
                                <th class="py-1.5 text-right text-xs">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Basic Salary</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->payrollEntry?->basic_salary ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Gross Salary</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->gross_pay, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Overtime Pay</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->payrollEntry?->overtime_pay ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Bonuses</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->payrollEntry?->total_bonus ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Incentives</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->payrollEntry?->total_incentive ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Deductions -->
                <div class="info-card p-3.5 bg-white">
                    <h2 class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2.5">Deductions</h2>
                    <table class="w-full info-table">
                        <thead>
                            <tr>
                                <th class="py-1.5 text-left text-xs">Description</th>
                                <th class="py-1.5 text-right text-xs">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Other Deductions</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->payrollEntry?->total_deduction ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Late Deduction</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->late_deductions, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">SSS Premium</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->sss_deduction, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">PhilHealth</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->philhealth_deduction, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 text-xs text-slate-600">Pag-IBIG Fund</td>
                                <td class="py-1.5 text-xs text-slate-800 text-right amount-column">₱{{ number_format($payroll->pagibig_deduction, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Net Salary Bar -->
            <div class="mt-4 net-salary-bar rounded-lg bg-slate-900 p-4 text-white print-break-inside">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-slate-300 uppercase tracking-wider font-medium">Net Salary</p>
                        <p class="text-2xl md:text-3xl font-bold tracking-tight mt-0.5">₱{{ number_format($payroll->net_pay, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">Payment Method</p>
                        <p class="text-xs font-medium text-slate-200">Bank Transfer</p>
                    </div>
                </div>
            </div>

            <!-- Itemized Payroll Entries -->
            <div class="mt-5 print-break-inside">
                <h2 class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2.5">Itemized Payroll Entries</h2>
                <div class="overflow-hidden rounded-lg border border-slate-200">
                    <table class="min-w-full info-table">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">Type</th>
                                <th class="px-4 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">Name</th>
                                <th class="px-4 py-2 text-right text-[10px] font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payroll->items as $item)
                                <tr class="border-t border-slate-100">
                                    <td class="px-4 py-2 text-xs text-slate-600">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium
                                            {{ $item->item_type === 'Bonus' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                            {{ $item->item_type === 'Incentive' ? 'bg-blue-50 text-blue-700' : '' }}
                                            {{ $item->item_type === 'Deduction' ? 'bg-rose-50 text-rose-700' : '' }}">
                                            {{ $item->item_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-xs text-slate-700 font-medium">{{ $item->item_name }}</td>
                                    <td class="px-4 py-2 text-xs text-slate-800 text-right amount-column">₱{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-5 text-center text-xs text-slate-400">No itemized entries recorded for this payroll.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-5 pt-3 border-t border-slate-200 text-center">
                <p class="text-[10px] text-slate-400">This is a computer-generated document. No signature is required.</p>
                <p class="text-[10px] text-slate-400 mt-0.5">NexaTech Payroll System &bull; Official Payslip</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="no-print mt-4 flex flex-col sm:flex-row items-center justify-center gap-2">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Payslip
            </button>
            <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </div>
</body>
</html>

