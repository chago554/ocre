<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('aggregate-daily-metrics')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/metrics_schedule.log'));;