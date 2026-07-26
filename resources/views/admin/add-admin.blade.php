@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8"> <!--container--->
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

        <div class="overflow-hidden rounded-[2rem] bg-white p-8 shadow-sm">
            <div class="mb-8 rounded-[2rem] border border-slate-200 bg-slate-50 p-6">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-blue-100 text-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                            <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="8.5" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20 8v6M23 11h-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Admin Information</p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-900">Fill in the details below to create a new admin account.</h2>
                        <p class="mt-2 text-sm text-slate-500">Use the existing admins database table to add a payroll system administrator.</p>
                    </div>
                </div>
            </div>

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
                        <div class="relative mt-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7.5 7.5 0 0112 15a7.5 7.5 0 016.879 2.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 pl-12 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        </div>
                        @error('name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email address</label>
                        <div class="relative mt-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a3 3 0 003.22 0L21 8m0 8V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2z" />
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 pl-12 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        </div>
                        @error('email') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Password</label>
                        <div class="relative mt-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-8v2m0 4h.01" />
                                    <rect x="6" y="11" width="12" height="8" rx="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11V8a3 3 0 016 0v3" />
                                </svg>
                            </span>
                            <input type="password" name="password" placeholder="••••••••" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 pl-12 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        </div>
                        @error('password') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Confirm password</label>
                        <div class="relative mt-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-8v2m0 4h.01" />
                                    <rect x="6" y="11" width="12" height="8" rx="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11V8a3 3 0 016 0v3" />
                                </svg>
                            </span>
                            <input type="password" name="password_confirmation" placeholder="••••••••" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 pl-12 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        </div>
                        @error('password_confirmation') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Role</label>
                        <div class="relative mt-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.1-.9-2-2-2H6.5A2.5 2.5 0 004 11.5v6A2.5 2.5 0 006.5 20h11A2.5 2.5 0 0020 17.5v-6A2.5 2.5 0 0017.5 9H14c-1.1 0-2 .9-2 2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8" />
                                </svg>
                            </span>
                            <select name="role" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 pl-12 pr-8 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="">-- Select role --</option>
                                <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Head HR" {{ old('role') === 'Head HR' ? 'selected' : '' }}>Head HR</option>
                                <option value="Assistant HR" {{ old('role') === 'Assistant HR' ? 'selected' : '' }}>Assistant HR</option>
                            </select>
                        </div>
                        @error('role') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex w-full sm:w-auto items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path d="M4 4h16v16H4z" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8 4v6h8V4" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8 14h8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Save admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection