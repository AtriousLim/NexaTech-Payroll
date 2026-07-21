@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="space-y-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-900">Edit employee</h1>
            <p class="mt-1 text-sm text-slate-500">Update employee details and save.</p>
        </div>

        <div class="overflow-hidden rounded-[1.25rem] bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.employees.update', ['employee' => $employee->id]) }}">
                @csrf
                @method('PATCH')

                <div class="grid gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">First name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('first_name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Last name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('last_name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Address</label>
                        <input type="text" name="address" value="{{ old('address', $employee->address) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('address') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Contact number</label>
                        <input type="text" name="contact_number" value="{{ old('contact_number', $employee->contact_number) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('contact_number') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="gmail" value="{{ old('gmail', $employee->gmail) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('gmail') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Username</label>
                        <input type="text" name="username" value="{{ old('username', $employee->username) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('username') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('password') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Position</label>
                        <select name="position_id" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm">
                            <option value="">-- Select position --</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ old('position_id', $employee->position_id) == $pos->id ? 'selected' : '' }}>{{ $pos->position_title }}</option>
                            @endforeach
                        </select>
                        @error('position_id') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm">
                            <option value="Active" {{ old('status', $employee->status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $employee->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('admin.employees') }}" class="text-sm text-slate-600">Cancel</a>
                    <button type="submit" class="rounded-full bg-blue-900 text-white px-4 py-2">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
