@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="space-y-4">
        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between mb-4">
                <div class="space-y-3">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Employee management</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Create employee profile</h1>
                    <p class="max-w-2xl text-sm text-slate-500">
                        Enter employee details and assign the correct department, position,
                        and credentials for payroll and attendance tracking.
                    </p>
                </div>
                <a href="{{ route('admin.employees') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                        <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back to Employees
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Employee information</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Essential details</h2>
                </div>
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">
                    Professional form
                </span>
            </div>

            @if(session('success'))
                <div class="border-b border-slate-200 bg-emerald-50 p-6 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="border-b border-slate-200 bg-rose-50 p-6 text-sm text-rose-700">
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

                <div class="space-y-4 p-6">
                    <div class="grid gap-3 sm:grid-cols-[1fr_0.95fr]">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Employee code</p>
                            <input type="text" value="{{ $nextEmployeeCode }}" readonly class="mt-4 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                            <p class="mt-3 text-sm text-slate-500">Automatically assigned when saving the employee.</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Status</p>
                            <select name="status" class="mt-4 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-center gap-2 border-b border-slate-200 pb-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 4z" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 20c0-3.31 2.69-6 6-6s6 2.69 6 6" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Personal information</p>
                                <p class="text-sm text-slate-500">Basic employee details and identifiers.</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">First name *</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                                @error('first_name') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Middle name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name (optional)" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                                @error('middle_name') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Last name *</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Dela Cruz" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                                @error('last_name') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Suffix</label>
                                <input type="text" name="suffix" value="{{ old('suffix') }}" placeholder="e.g. Jr., Sr., III" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                                @error('suffix') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Date of birth *</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                                @error('date_of_birth') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Gender *</label>
                                <select name="gender" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <option value="">-- Select gender --</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Civil status *</label>
                                <select name="civil_status" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <option value="">-- Select civil status --</option>
                                    <option value="Single" {{ old('civil_status') === 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') === 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('civil_status') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('civil_status') === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('civil_status') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Nationality *</label>
                                <select name="nationality" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <option value="">-- Select nationality --</option>
                                    <option value="Filipino" {{ old('nationality') === 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                    <option value="American" {{ old('nationality') === 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('nationality') === 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Chinese" {{ old('nationality') === 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                    <option value="Japanese" {{ old('nationality') === 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                    <option value="Indian" {{ old('nationality') === 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="Korean" {{ old('nationality') === 'Korean' ? 'selected' : '' }}>Korean</option>
                                    <option value="Thai" {{ old('nationality') === 'Thai' ? 'selected' : '' }}>Thai</option>
                                    <option value="Malaysian" {{ old('nationality') === 'Malaysian' ? 'selected' : '' }}>Malaysian</option>
                                    <option value="Singaporean" {{ old('nationality') === 'Singaporean' ? 'selected' : '' }}>Singaporean</option>
                                    <option value="Vietnamese" {{ old('nationality') === 'Vietnamese' ? 'selected' : '' }}>Vietnamese</option>
                                    <option value="Australian" {{ old('nationality') === 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Canadian" {{ old('nationality') === 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Other" {{ old('nationality') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('nationality') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Contact Number *</label>
                                <input type="tel" name="contact_number" value="{{ old('contact_number') }}" placeholder="+63 9XX XXX XXXX" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                                @error('contact_number') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Gmail *</label>
                                <input type="email" name="gmail" value="{{ old('gmail') }}" placeholder="employee@gmail.com" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" />
                                @error('gmail') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700">Address *</label>
                                <textarea name="address" placeholder="Enter full address" rows="2" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('address') }}</textarea>
                                @error('address') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-emerald-100 bg-emerald-50 p-5">
                        <div class="flex items-center gap-2 border-b border-emerald-200 pb-4">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 19a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Employment details</p>
                                <p class="text-sm text-slate-500">Department and position information.</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Department *</label>
                                <select id="department_id" name="department_id" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <option value="">-- Select department --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Position *</label>
                                <select id="position_id" name="position_id" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" disabled>
                                    <option value="">-- Select department first --</option>
                                </select>
                                @error('position_id') <div class="mt-2 text-sm text-rose-600">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-medium text-slate-700">Salary</label>
                            <input id="position_salary" type="text" readonly data-salaries="{{ json_encode($positions->mapWithKeys(function($pos){ return [$pos->id => $pos->basic_salary]; })->toArray()) }}" value="{{ old('position_id') ? ($positions->firstWhere('id', old('position_id'))->basic_salary ?? '') : '' }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none" />
                        </div>
                    </div>

                    <input type="hidden" name="role" value="employee">

                    <div class="flex flex-col gap-2 rounded-[1.5rem] border border-slate-200 bg-white p-5 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.employees') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">Save employee</button>
                    </div>
                </div>
            </form>

            <div id="salaries-data" data-salaries="{{ json_encode($positions->pluck('basic_salary', 'id')) }}"></div>
            <div id="positions-by-department" data-positions="{{ json_encode($positionsByDepartment) }}"></div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const departmentSelect = document.getElementById('department_id');
                    const positionSelect = document.getElementById('position_id');
                    const salaryInput = document.getElementById('position_salary');
                    
                    const salariesData = document.getElementById('salaries-data');
                    const positionsData = document.getElementById('positions-by-department');
                    
                    const salaries = JSON.parse(salariesData.getAttribute('data-salaries'));
                    const positionsByDepartment = JSON.parse(positionsData.getAttribute('data-positions'));

                    function updatePositions() {
                        const selectedDepartment = departmentSelect.value;
                        
                        // Clear existing options except the first one
                        while (positionSelect.options.length > 1) {
                            positionSelect.remove(1);
                        }

                        if (!selectedDepartment) {
                            positionSelect.disabled = true;
                            positionSelect.options[0].text = '-- Select department first --';
                            salaryInput.value = '';
                            return;
                        }

                        const departmentPositions = positionsByDepartment[selectedDepartment] || [];
                        
                        if (departmentPositions.length === 0) {
                            positionSelect.disabled = true;
                            positionSelect.options[0].text = '-- No positions available --';
                            salaryInput.value = '';
                            return;
                        }

                        positionSelect.options[0].text = '-- Select position --';
                        departmentPositions.forEach(function(pos) {
                            const option = document.createElement('option');
                            option.value = pos.id;
                            option.text = pos.position_title;
                            positionSelect.appendChild(option);
                        });

                        positionSelect.disabled = false;
                        
                        // Restore old selection if available
                        const oldPositionId = '{{ old("position_id") }}';
                        if (oldPositionId && positionsByDepartment[selectedDepartment].find(p => p.id == oldPositionId)) {
                            positionSelect.value = oldPositionId;
                        }
                        
                        updateSalary();
                    }

                    function updateSalary() {
                        if (salaryInput && positionSelect.value) {
                            salaryInput.value = salaries[positionSelect.value] ?? '';
                        } else {
                            salaryInput.value = '';
                        }
                    }

                    // Restore department selection if available
                    const oldDepartmentId = '{{ old("department_id") }}';
                    if (oldDepartmentId) {
                        departmentSelect.value = oldDepartmentId;
                        updatePositions();
                    }

                    departmentSelect.addEventListener('change', updatePositions);
                    positionSelect.addEventListener('change', updateSalary);
                });
            </script>
        </div>
    </div>
</div>

@endsection
