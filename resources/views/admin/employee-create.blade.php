@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Employee management</p>
                <h1 class="text-3xl font-semibold text-slate-900">Create employee profile</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">Enter employee details and assign the correct department, position, and credentials for payroll and attendance tracking.</p>
            </div>
            <div class="flex justify-start md:justify-end">
                <a href="{{ route('admin.employees') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white" aria-label="Exit to employee list">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                        <path d="M12 4L6 10L12 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Exit
                </a>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.45fr_0.65fr]">
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Employee information</p>
                        <h2 class="text-2xl font-semibold text-slate-900">Essential details</h2>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Professional form</span>
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

                <form method="POST" action="{{ route('admin.employees.store') }}">
                    @csrf

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Employee code</label>
                            <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="EMP-004" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('employee_code') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status</label>
                            <select name="status" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">First name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('first_name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Last name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Dela Cruz" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('last_name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="1234 Main St, City, Country" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('address') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Contact number</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('contact_number') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" name="gmail" value="{{ old('gmail') }}" placeholder="name@example.com" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('gmail') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="juancruz" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('username') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Password</label>
                            <input type="password" name="password" placeholder="••••••••" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('password') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Position</label>
                            <select name="position_id" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="">-- Select position --</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->position_title }}</option>
                                @endforeach
                            </select>
                            @error('position_id') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <input type="hidden" name="role" value="employee">

                    <div class="mt-10 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-6">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl bg-white p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Record info</p>
                                <p class="mt-2 text-sm text-slate-600">Save employee information once complete. Required fields are marked clearly.</p>
                            </div>
                            <div class="rounded-3xl bg-white p-5 shadow-sm">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Position</p>
                                <p class="mt-2 text-sm text-slate-600">Assign the employee's role and department to ensure accurate reporting.</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('admin.employees') }}" class="inline-flex w-full sm:w-auto items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Cancel</a>
                            <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">Save employee</button>
                        </div>
                    </div>
                </form>
            </div>

            <aside class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <div class="space-y-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Form guide</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-900">Quick tips</h2>
                        <p class="mt-2 text-sm text-slate-600">Follow these best practices while adding employee records.</p>
                    </div>

                    <div class="rounded-3xl bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">What to include</p>
                        <ul class="mt-3 space-y-3 text-sm text-slate-600">
                            <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Unique employee code</li>
                            <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Valid email and username</li>
                            <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Assigned position for payroll</li>
                        </ul>
                    </div>

                    <div class="rounded-3xl bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Need help?</p>
                        <ul class="mt-3 space-y-3 text-sm text-slate-600">
                            <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-slate-400"></span>Use a professional naming style</li>
                            <li class="flex items-start gap-2"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-slate-400"></span>Keep the password secure</li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

@endsection
