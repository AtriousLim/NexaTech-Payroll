@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 font-medium text-emerald-800">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 font-medium text-amber-800">{{ session('warning') }}</div>
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
</script>
@endsection