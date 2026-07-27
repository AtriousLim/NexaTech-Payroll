<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PayrollHistory;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

echo "=== PAYROLL HISTORY (last 10) ===\n";
$histories = PayrollHistory::with('employee')->latest()->limit(10)->get();
foreach ($histories as $h) {
    echo "ID:{$h->id} EmpID:{$h->employee_id} Gross:{$h->gross_pay} Net:{$h->net_pay} Late:{$h->late_deductions} SSS:{$h->sss_deduction} PhilH:{$h->philhealth_deduction} PagIBIG:{$h->pagibig_deduction} Status:{$h->status}\n";
}

echo "\n=== PAYROLLS (last 10) ===\n";
$payrolls = Payroll::latest()->limit(10)->get();
foreach ($payrolls as $p) {
    echo "ID:{$p->id} EmpID:{$p->employee_id} HistID:" . ($p->payroll_history_id ?? 'NULL') . " GrossSalary:{$p->gross_salary} NetSalary:{$p->net_salary} BasicSalary:{$p->basic_salary} TotalBonus:{$p->total_bonus} TotalDed:{$p->total_deduction} LateDed:{$p->late_deduction} Overtime:{$p->overtime_pay}\n";
}

echo "\n=== EMPLOYEES WITH POSITIONS ===\n";
$emps = Employee::with('position')->get();
foreach ($emps as $e) {
    $pos = $e->position;
    echo "ID:{$e->id} Code:{$e->employee_code} Name:{$e->first_name} {$e->last_name} ";
    if ($pos) {
        echo "Position:{$pos->position_title} Salary:{$pos->basic_salary}";
    } else {
        echo "NO POSITION ASSIGNED!";
    }
    echo "\n";
}

echo "\n=== ATTENDANCE COUNT PER EMPLOYEE ===\n";
$counts = DB::table('attendances')
    ->select('employee_id', 
        DB::raw('count(*) as total'),
        DB::raw('SUM(CASE WHEN status IN ("Present","Late") THEN 1 ELSE 0 END) as present_days'),
        DB::raw('SUM(late_minutes) as total_late'),
        DB::raw('SUM(overtime_minutes) as total_ot'))
    ->groupBy('employee_id')
    ->get();
foreach ($counts as $c) {
    echo "EmpID:{$c->employee_id} TotalRecords:{$c->total} PresentDays:{$c->present_days} LateMins:{$c->total_late} OTMins:{$c->total_ot}\n";
}

