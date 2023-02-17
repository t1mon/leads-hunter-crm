<?php

namespace App\Commands\V2\Project\Export;

use App\Models\Project\Export;

class DownloadFileHandler
{
    /**
     * DownloadFileHandler constructor.
     */
    public function __construct(

    )
    {
    }

    /**
     * @param DownloadFileCommand $command
     */
    public function handle(DownloadFileCommand $command)
    {
        $export = Export::findOrFail($command->export_id);
        return Export::getStorageDiskInstance()->download($export->name);
    }
}
