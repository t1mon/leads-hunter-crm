<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\OpenApi(
 *  @OA\Info(
 *      title="Документация по API",
 *      version="1.0.0",
 *      description="Документация по API для Leads Hunter CRM",
 *  ),
 *  @OA\PathItem(
 *      path="/"
 *  )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
