<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Models\Appointment;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(ScheduleRequest $request)
    {
        $startDate = $request->get('date');
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');
        $timezone = $request->get('timezone');
        $miamiTimezone = 'America/New_York';
        $parsedStartTime = Carbon::createFromFormat('Y-m-d H:i', $startDate . ' ' . $startTime, $timezone);
        $parsedEndTime = Carbon::createFromFormat('Y-m-d H:i', $startDate . ' ' . $endTime, $timezone);

        $availableHour = WorkSchedule::whereBetween('date', [$parsedStartTime->format('Y-m-d'), $parsedEndTime->format('Y-m-d')])
            ->whereTime('start_time', '>=', $parsedStartTime->setTimezone($miamiTimezone)->toTimeString())
            ->whereTime('end_time', '<=', $parsedEndTime->setTimezone($miamiTimezone)->toTimeString())
            ->where('is_available', true)
            ->first();

        return ApiResponse::successWithData($availableHour);
    }

    /**
     * Create an appointment
     */
    public function store(Request $request)
    {
        $id = $request->get('id');
        $schedule = WorkSchedule::find($id);
        $schedule->update(['is_available' => false]);


        $appointment = Appointment::create([
            'employee_id' => $schedule->employee_id,
            'work_schedule_id' => $schedule->id,
            'appointment_date' => $schedule->date,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
        ]);

        return ApiResponse::successWithData($appointment);
    }
}
