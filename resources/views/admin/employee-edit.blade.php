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
                        <label class="block text-sm font-medium text-slate-700">Middle name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $employee->middle_name ?? '') }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('middle_name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Last name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('last_name') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Suffix</label>
                        <input type="text" name="suffix" value="{{ old('suffix', $employee->suffix ?? '') }}" placeholder="e.g. Jr., Sr., III" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('suffix') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
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
                        <label class="block text-sm font-medium text-slate-700">Gmail</label>
                        <input type="email" name="gmail" value="{{ old('gmail', $employee->gmail) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('gmail') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Date of birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth) }}" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        @error('date_of_birth') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Gender</label>
                        <select name="gender" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm">
                            <option value="">-- Select gender --</option>
                            <option value="Male" @if(old('gender') === 'Male' || strtolower($employee->gender ?? '') === 'male') selected @endif>Male</option>
                            <option value="Female" @if(old('gender') === 'Female' || strtolower($employee->gender ?? '') === 'female') selected @endif>Female</option>
                            <option value="Other" @if(old('gender') === 'Other' || strtolower($employee->gender ?? '') === 'other') selected @endif>Other</option>
                        </select>
                        @error('gender') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Civil status</label>
                        <select name="civil_status" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm">
                            <option value="">-- Select civil status --</option>
                            <option value="Single" @if(old('civil_status') === 'Single' || strtolower($employee->civil_status ?? '') === 'single') selected @endif>Single</option>
                            <option value="Married" @if(old('civil_status') === 'Married' || strtolower($employee->civil_status ?? '') === 'married') selected @endif>Married</option>
                            <option value="Divorced" @if(old('civil_status') === 'Divorced' || strtolower($employee->civil_status ?? '') === 'divorced') selected @endif>Divorced</option>
                            <option value="Widowed" @if(old('civil_status') === 'Widowed' || strtolower($employee->civil_status ?? '') === 'widowed') selected @endif>Widowed</option>
                        </select>
                        @error('civil_status') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nationality</label>
                        <select name="nationality" class="mt-1 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm">
                            <option value="">-- Select nationality --</option>
                            <option value="Filipino" {{ old('nationality', $employee->nationality) === 'Filipino' ? 'selected' : '' }}>Filipino</option>
                            <option value="American" {{ old('nationality', $employee->nationality) === 'American' ? 'selected' : '' }}>American</option>
                            <option value="British" {{ old('nationality', $employee->nationality) === 'British' ? 'selected' : '' }}>British</option>
                            <option value="Chinese" {{ old('nationality', $employee->nationality) === 'Chinese' ? 'selected' : '' }}>Chinese</option>
                            <option value="Japanese" {{ old('nationality', $employee->nationality) === 'Japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="Indian" {{ old('nationality', $employee->nationality) === 'Indian' ? 'selected' : '' }}>Indian</option>
                            <option value="Korean" {{ old('nationality', $employee->nationality) === 'Korean' ? 'selected' : '' }}>Korean</option>
                            <option value="Thai" {{ old('nationality', $employee->nationality) === 'Thai' ? 'selected' : '' }}>Thai</option>
                            <option value="Malaysian" {{ old('nationality', $employee->nationality) === 'Malaysian' ? 'selected' : '' }}>Malaysian</option>
                            <option value="Singaporean" {{ old('nationality', $employee->nationality) === 'Singaporean' ? 'selected' : '' }}>Singaporean</option>
                            <option value="Vietnamese" {{ old('nationality', $employee->nationality) === 'Vietnamese' ? 'selected' : '' }}>Vietnamese</option>
                            <option value="Australian" {{ old('nationality', $employee->nationality) === 'Australian' ? 'selected' : '' }}>Australian</option>
                            <option value="Canadian" {{ old('nationality', $employee->nationality) === 'Canadian' ? 'selected' : '' }}>Canadian</option>
                            <option value="Other" {{ old('nationality', $employee->nationality) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('nationality') <div class="mt-1 text-sm text-rose-600">{{ $message }}</div> @enderror
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
