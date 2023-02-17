<?php

namespace App\Commands\V2\Project\Export;

class DownloadFileCommand
{
    /**
     * DownloadFileCommand constructor.
     */
    public function __construct(
        public $export_token)
    {
    }
}
