<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Automated Operations & Disaster Recovery
|--------------------------------------------------------------------------
*/

// Daily DB Backup at 02:00 AM (Database only to save space)
Schedule::command('backup:run --only-db')->dailyAt('02:00')->withoutOverlapping();

// Weekly Full Backup on Sundays (Database + Files)
Schedule::command('backup:run')->sundays()->at('03:00');

// Retention & Health Monitoring
Schedule::command('backup:clean')->dailyAt('04:00');
Schedule::command('backup:monitor')->weeklyOn(0, '08:00');

/*
|-------------------------------------------------------------------------
| Maintenance & Compliance
|--------------------------------------------------------------------------
*/

// Reporting & Cleanup
Schedule::command('report:generate-daily')->dailyAt('06:00');
Schedule::command('session:cleanup')->daily();
Schedule::command('auth:clear-resets')->daily();

// Hourly Pending Order Cleanup
Schedule::command('order:cleanup-pending')->hourly();

// Weekly Maintenance
Schedule::command('log:rotate')->weekly();
Schedule::command('notification:prune')->weekly();

// Monthly Compliance Archiving
Schedule::command('audit:archive')->monthly();