<?php
use Illuminate\Support\Facades\Schedule;

// 1. Run the database and file backup every night at 2:00 AM
Schedule::command('backup:run')->dailyAt('02:00')->withoutOverlapping();

// 2. Clean up old backups based on your retention policy every night at 3:00 AM
Schedule::command('backup:clean')->dailyAt('03:00');

// 3. Run our custom abandoned order cleanup script every hour
Schedule::command('orders:cleanup-pending')->hourly();

// 4. Clean up expired user password reset tokens daily
Schedule::command('auth:clear-resets')->daily();