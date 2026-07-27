@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div id="success-alert" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 font-medium text-emerald-800">
            {{ session('success') }}
            <button type="button" onclick="this.parentElement.style.display='none'" class="float-right text-emerald-600 hover:text-emerald-800">✕</button>
        </div>
    @endif

    @if(session('warning'))
        <div id="warning-alert" class="rounded-xl border border-amber-200 bg-amber-50 p-4 font-medium text-amber-800">
            {{ session('warning') }}
            <button type="button" onclick="this.parentElement.style.display='none'" class="float-right text-amber-600 hover:text-amber-800">✕</button>
        </div>
    @endif

    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Payroll History</h1>
            <p class="mt-1 text-slate-500">Review, approve, edit, and print payroll records.</p>
        </div>
        <form method="POST" action="{{ route('admin.payroll.approve-all') }}" id="approve-all-form">
            @csrf
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 font-semibold text-white transition hover:bg-emerald-700">Accept All Pending Transactions</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Payroll Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Pay Period</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Gross Pay</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Net Pay</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Payment Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Processed By</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($payrolls as $payroll)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">{{ $payroll->payrollEntry?->payroll_number ?? 'PR-' . $payroll->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $payroll->employee?->first_name }} {{ $payroll->employee?->last_name }}</div>
                                <div class="text-xs text-slate-500">{{ $payroll->employee?->employee_code }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $payroll->cutoff_start }} to {{ $payroll->cutoff_end }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">₱{{ number_format($payroll->gross_pay, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-emerald-700">₱{{ number_format($payroll->net_pay, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $payroll->status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $payroll->status }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $payroll->payment_date ? \Carbon\Carbon::parse($payroll->payment_date)->format('M d, Y h:i A') : 'Pending' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $payroll->processed_by ? 'Admin #' . $payroll->processed_by : 'N/A' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('admin.payroll.payslip', $payroll) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">View Payslip</a>
                                    <a href="{{ route('admin.payroll.edit', $payroll) }}" class="rounded-lg border border-blue-300 px-3 py-1.5 text-sm font-semibold text-blue-700 hover:bg-blue-50">Edit Payroll</a>
                                    @if($payroll->status !== 'Paid')
                                        <form method="POST" action="{{ route('admin.payroll.approve', $payroll) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-emerald-700">Approve</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.payroll.destroy', $payroll) }}" class="inline-block" onsubmit="return confirm('Delete this payroll record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-500">No payroll history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-slate-600">
                Showing {{ $payrolls->firstItem() ?? 0 }} to {{ $payrolls->lastItem() ?? 0 }} of {{ $payrolls->total() }} records
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div class="text-sm text-slate-600 sm:mr-4">
                    Page {{ $payrolls->currentPage() }} of {{ $payrolls->lastPage() }}
                </div>
                <div class="inline-flex overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-slate-200">
                    <a href="{{ $payrolls->previousPageUrl() ?? '#' }}"
                       class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 {{ $payrolls->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}"
                       aria-label="Previous page">
                        ‹
                    </a>
                    <span class="inline-flex items-center justify-center px-6 py-2 text-sm font-semibold text-slate-900 bg-blue-100">
                        {{ $payrolls->currentPage() }}
                    </span>
                    <a href="{{ $payrolls->nextPageUrl() ?? '#' }}"
                       class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 {{ $payrolls->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}"
                       aria-label="Next page">
                        ›
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Handle approve all form
    document.getElementById('approve-all-form')?.addEventListener('submit', function (event) {
        event.preventDefault();
        Swal.fire({
            title: 'Approve all pending payrolls?',
            text: 'This will mark every pending payroll as Paid and update the payment date.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve all',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
    
    // Auto-dismiss success alerts after 5 seconds
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.display = 'none';
        }, 5000);
        
        // Mark this message as seen so it doesn't show again on refresh
        if (sessionStorage.getItem('payroll-success-message-seen')) {
            successAlert.style.display = 'none';
        } else {
            sessionStorage.setItem('payroll-success-message-seen', 'true');
        }
    }
    
    // Auto-dismiss warning alerts after 5 seconds
    const warningAlert = document.getElementById('warning-alert');
    if (warningAlert) {
        setTimeout(() => {
            warningAlert.style.display = 'none';
        }, 5000);
    }
</script>
@endsection