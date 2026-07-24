@extends('layouts.admin')

@section('content')

<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Payroll Management
            </h1>

            <p class="text-slate-500 mt-1">
                Process employee payroll and generate payslips.
            </p>

        </div>

        <button
            class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2 rounded-lg">

            Pay All Employees

        </button>

    </div>

   <form method="GET"
      action="{{ route('admin.payroll') }}"
      class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-8">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search employee..."
        class="border rounded-lg px-4 py-2">

    <select
        id="department"
        name="department"
        class="border rounded-lg px-4 py-2">

        <option value="">Select Department</option>

        @foreach($departments as $department)

            <option
                value="{{ $department->id }}"
                @selected(request('department') == $department->id)>

                {{ $department->department_name }}

            </option>

        @endforeach

    </select>

    <select
        id="position"
        name="position"
        class="border rounded-lg px-4 py-2"
        disabled>

        <option value="">
            Select Department First
        </option>

    </select>

    <select
        name="salary"
        class="border rounded-lg px-4 py-2">

        <option value="high"
            {{ request('salary') == 'high' ? 'selected' : '' }}>
            Highest Salary
        </option>

        <option value="low"
            {{ request('salary') == 'low' ? 'selected' : '' }}>
            Lowest Salary
        </option>

    </select>

    <button
        class="bg-blue-900 hover:bg-blue-800 text-white rounded-lg">

        Apply Filters

    </button>

</form>

    <div class="overflow-x-auto mt-8">

        <table class="min-w-full">

            <thead class="bg-slate-100">

                <tr>

                    <th class="text-left p-4">Employee Code</th>

                    <th class="text-left p-4">Employee Name</th>

                    <th class="text-left p-4">Department</th>

                    <th class="text-left p-4">Position</th>

                    <th class="text-right p-4">Basic Salary</th>

                    <th class="text-center p-4">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($employees as $employee)

                <tr class="border-b">

                    <td class="p-4">

                        {{ $employee->employee_code }}

                    </td>

                    <td class="p-4">

                        {{ $employee->first_name }}
                        {{ $employee->last_name }}

                    </td>

                    <td class="p-4">

                        {{ $employee->department->department_name }}

                    </td>

                    <td class="p-4">

                        {{ $employee->position->position_title }}

                    </td>

                    <td class="text-right p-4 font-semibold">

                        ₱{{ number_format($employee->position->basic_salary,2) }}

                    </td>

                    <td class="text-center">

                       <a
                            href="{{ route('admin.payroll.preview',$employee->id) }}"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg inline-block">

                            PAY

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center p-8 text-slate-500">

                        No employees found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

const department = document.getElementById('department');
const position = document.getElementById('position');

department.addEventListener('change', function () {

    position.innerHTML =
        '<option>Loading...</option>';

    position.disabled = true;

    if(this.value === '')
    {
        position.innerHTML =
            '<option>Select Department First</option>';

        return;
    }

    fetch("{{ url('/admin/positions') }}/" + this.value)

        .then(response => response.json())

        .then(data => {

            position.innerHTML =
                '<option value="">All Positions</option>';

            data.forEach(item => {

                position.innerHTML +=

                `<option value="${item.id}">
                    ${item.position_title}
                </option>`;

            });

            position.disabled = false;

        });

});

</script>

<script>

window.addEventListener('load', function () {

    const departmentId = "{{ request('department') }}";
    const positionId = "{{ request('position') }}";

    if(departmentId !== '')
    {
        fetch("{{ url('/admin/positions') }}/" + departmentId)

            .then(response => response.json())

            .then(data => {

                position.innerHTML =
                    '<option value="">All Positions</option>';

                data.forEach(item => {

                    let selected =
                        item.id == positionId
                        ? 'selected'
                        : '';

                    position.innerHTML +=

                    `<option value="${item.id}" ${selected}>
                        ${item.position_title}
                    </option>`;

                });

                position.disabled = false;

            });
    }

});

</script>

@endsection