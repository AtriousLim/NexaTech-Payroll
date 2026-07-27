@extends('layouts.admin')

@section('content')

<div class="rounded-2xl bg-white p-6 shadow-sm sm:p-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">System history</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Activity Log</h1>
            <p class="mt-2 text-slate-600">Tracks administrator sign-ins and changes to employee and admin records.</p>
        </div>
        <span class="inline-flex w-fit rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
            {{ $logs->total() }} {{ $logs->total() === 1 ? 'activity' : 'activities' }}
        </span>
    </div>

    @if($logs->isEmpty())
        <div class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
            <p class="font-semibold text-slate-700">No activity recorded yet.</p>
            <p class="mt-2 text-sm text-slate-500">New administrator and employee actions will appear here.</p>
        </div>
    @else
        <div class="mt-8 overflow-x-auto rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Administrator</th>
                        <th class="px-5 py-4">Action</th>
                        <th class="px-5 py-4">Date and time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white text-slate-700">
                    @foreach($logs as $log)
                        <tr>
                            <td class="px-5 py-4 font-medium text-slate-900">{{ $log->admin_name ?? 'Deleted administrator' }}</td>
                            <td class="px-5 py-4">{{ $log->action }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y g:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 border-t border-slate-200 bg-slate-50 -mx-8 px-8 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-slate-600">
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} records
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <div class="text-sm text-slate-600 sm:mr-4">
                    Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                </div>
                <div class="inline-flex overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-slate-200">
                    <a href="{{ $logs->previousPageUrl() ?? '#' }}"
                       class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 {{ $logs->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}"
                       aria-label="Previous page">
                        ‹
                    </a>
                    <span class="inline-flex items-center justify-center px-6 py-2 text-sm font-semibold text-slate-900 bg-blue-100">
                        {{ $logs->currentPage() }}
                    </span>
                    <a href="{{ $logs->nextPageUrl() ?? '#' }}"
                       class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 {{ $logs->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}"
                       aria-label="Next page">
                        ›
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
