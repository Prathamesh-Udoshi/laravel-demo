<?php

namespace App\Observers;

use App\Jobs\ChunkWeeklyContentJob;
use App\Models\WeeklyContent;

class WeeklyContentObserver
{
    /**
     * Handle the WeeklyContent "saved" event.
     */
    public function saved(WeeklyContent $weeklyContent): void
    {
        // Only trigger embedding chunking if summary changed
        if ($weeklyContent->wasChanged('summary') || $weeklyContent->wasRecentlyCreated) {
            ChunkWeeklyContentJob::dispatchSync($weeklyContent);
        }
    }
}
