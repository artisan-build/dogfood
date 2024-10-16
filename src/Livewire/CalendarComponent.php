<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class CalendarComponent extends Component
{
    public $months;

    public string $range = '';

    public function mount(): void
    {
        $this->months = $this->generateCalendar('2024-10', '2025-10');
    }

    public function generateCalendar(string $startMonth, string $endMonth): Collection
    {
        $startDate = Carbon::createFromFormat('Y-m', $startMonth)?->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $endMonth)?->endOfMonth();

        $months = collect();

        while ($startDate->lessThanOrEqualTo($endDate)) {
            $monthKey = $startDate->format('Y-m');
            $weeks = [];

            // Start the loop at the first Sunday before or on the first day of the month
            $weekStart = $startDate->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);

            while ($weekStart->lessThanOrEqualTo($startDate->copy()->endOfMonth())) {
                $week = [
                    'sunday' => ['date' => $date = $weekStart, 'number' => $date->format('j'), 'today' => $date->isToday()],
                    'monday' => ['date' => $date = $weekStart->copy()->addDays(1), 'number' => $date->format('j'), 'today' => $date->isToday()],
                    'tuesday' => ['date' => $date = $weekStart->copy()->addDays(2), 'number' =>  $date->format('j'), 'today' => $date->isToday()],
                    'wednesday' => ['date' => $date = $weekStart->copy()->addDays(3), 'number' =>  $date->format('j'), 'today' => $date->isToday()],
                    'thursday' => ['date' => $date = $weekStart->copy()->addDays(4), 'number' =>  $date->format('j'), 'today' => $date->isToday()],
                    'friday' => ['date' => $date = $weekStart->copy()->addDays(5), 'number' =>  $date->format('j'), 'today' => $date->isToday()],
                    'saturday' => ['date' => $date = $weekStart->copy()->addDays(6), 'number' =>  $date->format('j'), 'today' => $date->isToday()],
                ];

                $weeks[] = $week;
                $weekStart->addWeek();
            }

            $months->put($monthKey, [
                'weeks' => $weeks,
                'title' => $startDate->format('F Y'),
                'previous' => Carbon::createFromFormat('Y-m', $monthKey)?->subMonth()->format('Y-m'),
                'next' => Carbon::createFromFormat('Y-m', $monthKey)?->addMonth()->format('Y-m'),
            ]);
            $startDate->addMonth();
        }

        return $months;
    }

    public function render()
    {
        return view('hallway-flux::livewire.calendar')->layout('hallway-flux::layouts.app');
    }
}
