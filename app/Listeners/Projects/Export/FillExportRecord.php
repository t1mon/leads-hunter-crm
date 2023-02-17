<?php

namespace App\Listeners\Projects\Export;

use App\Events\Projects\Export\ExportFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Repositories\Project\Export\Repository as ExportRepository;

class FillExportRecord
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private ExportRepository $exportRepository
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Projects\Export\ExportFinished  $event
     * @return void
     */
    public function handle(ExportFinished $event)
    {
        $this->exportRepository->finish($event->export);

        // TODO: Отправка уведомления
        // ...
    }
}
