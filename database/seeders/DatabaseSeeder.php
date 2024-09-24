<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    const TIMEZONE = 'America/New_York';
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 3; $i++) {
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addMonths(2);
            $employee = Employee::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'timezone' => self::TIMEZONE
            ]);

            // populate schedule work for each day from month
            while ($startDate->lessThan($endDate)) {
                if ($startDate->isWeekday()) {
                    for ($hour = 8; $hour < 16; $hour++) { // 8 AM - 4 PM
                        $startTime = Carbon::createFromTime($hour, 0, 0, self::TIMEZONE);
                        $endTime = $startTime->copy()->addHour();

                        $isAvailable = ($hour != 14); // 2 PM lunch time

                        WorkSchedule::create([
                            'employee_id' => $employee->id,
                            'date' => $startDate->toDateString(),
                            'start_time' => $startTime->toTimeString(),
                            'end_time' => $endTime->toTimeString(),
                            'is_available' => $isAvailable
                        ]);
                    }
                }
                $startDate->addDay();
            }

            // get the available hours for each employee the first week of the first month
            $startOfMonth = $startDate->startOfMonth();
            $endOfWeek = $startOfMonth->copy()->addWeek();
            $this->getSchedules($startOfMonth, $endOfWeek, $employee);

            // get the available hours for each employee the first week of the second month
            $startOfMonth = $endDate->startOfMonth();
            $endOfWeek = $startOfMonth->copy()->addWeek();
            $this->getSchedules($startOfMonth, $endOfWeek, $employee);
        }
    }

    private function getSchedules($startOfMonth, $endOfWeek, $employee){
        $availablesSchedules =  WorkSchedule::where('is_available', true)
        ->whereBetween('date', [$startOfMonth, $endOfWeek])
            ->where('employee_id', $employee->id)->get();

        $this->createAppointments($availablesSchedules, $employee);
    }

    private function createAppointments($availablesSchedules, $employee){
        $randomAvailablesSchedules = $availablesSchedules->random(10);
            foreach ($randomAvailablesSchedules as $schedule) {

                // mark as unaivalable
                $schedule->update(['is_available' => false]);

                // create appointment
                Appointment::create([
                    'employee_id' => $employee->id,
                    'work_schedule_id' => $schedule->id,
                    'appointment_date' => $schedule->date,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                ]);
            }
    }
}
