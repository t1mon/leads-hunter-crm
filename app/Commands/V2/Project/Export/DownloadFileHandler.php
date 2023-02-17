<?php

namespace App\Commands\V2\Project\Export;

use App\Models\Project\Export;
use App\Repositories\Project\Export\ReadRepository as ExportReadRepository;

class DownloadFileHandler
{
    /**
     * DownloadFileHandler constructor.
     */
    public function __construct(
        private ExportReadRepository $exportReadRepository,
    )
    {
    }

    /**
     * @param DownloadFileCommand $command
     */
    public function handle(DownloadFileCommand $command)
    {
        $export = $this->exportReadRepository->findByToken(token: $command->export_token, fail: true, with: ['project', 'user']);
        return Export::getStorageDiskInstance()->download($export->name);
    }
}
