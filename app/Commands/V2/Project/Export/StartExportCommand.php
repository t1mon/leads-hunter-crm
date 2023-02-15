<?php

namespace App\Commands\V2\Project\Export;

use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;
use App\Models\User;

class StartExportCommand
{
    public int $project;
    public JournalRequest $request;
    public User|null $user;
    
    public function __construct(
       int $project,
       JournalRequest $request
    )
    {
        $this->project= $project;
        $this->request = $request;
        $this->user = $request->user();
    }
}
