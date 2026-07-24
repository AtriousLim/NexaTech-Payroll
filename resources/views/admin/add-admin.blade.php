@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-10">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Admin management</p>
                <h1 class="text-3xl font-semibold text-slate-900">Add admin user</h1>
                <p class="mt-1 max-w-2xl text-sm text-slate-500">
                    Create a new administrator account for the payroll system using the existing admins database table.
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-[3rem] bg-white p-8 shadow-sm">
            @if(session('success'))
                <div class="mb-6 rounded-3xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <strong class="block font-semibold mb-2">Please fix the following errors:</strong>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.add-admin.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Full name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        @error('name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        @error('email') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Password</label>
                        <input type="password" name="password" placeholder="••••••••" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        @error('password') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Confirm password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        @error('password_confirmation') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Role</label>
                        <select name="role" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">-- Select role --</option>
                            <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Head HR" {{ old('role') === 'Head HR' ? 'selected' : '' }}>Head HR</option>
                            <option value="Assistant HR" {{ old('role') === 'Assistant HR' ? 'selected' : '' }}>Assistant HR</option>
                        </select>
                        @error('role') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex w-full sm:w-auto items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">Save admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection