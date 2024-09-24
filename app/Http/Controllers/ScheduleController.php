<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees =  Employee::all();
        return view('schedules', compact('employees'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $employeeId = $request->get('id');
        $timezone = $request->get('timezone');

        $availableHours = WorkSchedule::where('employee_id', $employeeId)
            ->where('is_available', true)
            ->get();

        $availableHours = $this->getDataEmployees($availableHours, $timezone);

        return view('availables', compact('availableHours'));
    }

    /**
     * Show a report from schedules avalilables and not availables
     */
    public function report()
    {
        $employees = Employee::with(['workSchedules' => function ($query) {
            $query->select('employee_id')
                ->selectRaw('
                SUM(TIMESTAMPDIFF(HOUR, start_time, end_time)) AS total_active_hours,
                SUM(CASE WHEN is_available = 0 THEN TIMESTAMPDIFF(HOUR, start_time, end_time) ELSE 0 END) AS total_inactive_hours
            ')
                ->groupBy('employee_id');
        }])->get();
        return view('report', compact('employees'));
    }

    /**
     * Show a report from employees avalilables and not availables given data range
     */
    public function reportByDates(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');
        $timezone = $request->get('timezone');
        $miamiTimezone = 'America/New_York';
        $parsedStartTime = Carbon::createFromFormat('Y-m-d H:i', $startDate . ' ' . $startTime, $timezone);
        $parsedEndTime = Carbon::createFromFormat('Y-m-d H:i', $endDate . ' ' . $endTime, $timezone);


        $availableHours = WorkSchedule::whereBetween('date', [$parsedStartTime->format('Y-m-d'), $parsedEndTime->format('Y-m-d')])
        ->whereTime('start_time', '>=', $parsedStartTime->setTimezone($miamiTimezone)->toTimeString())
        ->whereTime('end_time', '<=', $parsedEndTime->setTimezone($miamiTimezone)->toTimeString())
        ->where('is_available', true)
        ->get();
        $availableHours = $this->getDataEmployees($availableHours, $timezone);
        return view('availables', compact('availableHours'));
    }

    /**
     * Append attributes to items in collection
     */
    private function getDataEmployees(Collection $availableHours, string $timezone)
    {
        return $availableHours->transform(function ($available) use ($timezone) {
            $originalTimezone = $available->employee->timezone;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $available->date->format('Y-m-d') . ' ' . $available->start_time->format('H:i:s'), $originalTimezone);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $available->date->format('Y-m-d') . ' ' . $available->end_time->format('H:i:s'), $originalTimezone);
            $available->local_start_time = $start->setTimezone($timezone)->format('H:i');
            $available->local_end_time = $end->setTimezone($timezone)->format('H:i');
            return $available;
        });
    }
}
