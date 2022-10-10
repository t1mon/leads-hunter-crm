<?php

namespace App\Services\Project;

use App\Http\Resources\Project;
use App\Repositories\Project\ReadRepository;

class ProjectService{
    public function __construct(
        private ReadRepository $readRepository
    )
    {}

    public function findById(int $id, bool $fail = false): ?Project
    {
        
    } //findById
};

?>