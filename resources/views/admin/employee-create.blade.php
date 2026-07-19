@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-20">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Employee management</p>
    <h1 class="text-3xl font-semibold text-slate-900">Create employee profile</h1>
    <p class="mt-1 max-w-2xl text-sm text-slate-500">
        Enter employee details and assign the correct department, position,
        and credentials for payroll and attendance tracking.
    </p>
</div>

<div class="mt-10 grid gap-10 xl:grid-cols-[1.45fr_0.65fr]">
    <div class="overflow-hidden rounded-[3rem] bg-white p-8 shadow-sm">
                <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Employee information</p>
                        <h2 class="text-2xl font-semibold text-slate-900">Essential details</h2>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs 
                    font-semibold uppercase tracking-[0.2em] text-blue-700">Professional form</span>
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

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Employee code</label>
                            <input type="text" value="{{ $nextEmployeeCode }}" readonly class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-slate-100 focus:outline-none" />
                            <p class="mt-2 text-sm text-slate-500">Automatically assigned when saving the employee.</p>
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

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Confirm password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            @error('password_confirmation') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div class="sm:col-span-10 mt-2">
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

                    <div class="mt-16 rounded-[1.75rem] bg-slate-50 p-6">
                        <div class="grid gap-2 sm:grid-cols-2">
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

        </div>
    </div>
</div>



@endsection
