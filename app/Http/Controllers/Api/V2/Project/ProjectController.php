<?php

namespace App\Http\Controllers\Api\V2\Project;

use App\Commands\V2\Project\Journal\ExportCommand;
use App\Commands\V2\Project\Journal\ExportHandler;
use App\Commands\V2\Project\Journal\GetVariantsCommand;
use App\Commands\V2\Project\Journal\GetVariantsHandler;
use App\Commands\V2\Project\Journal\JournalCommand;
use App\Commands\V2\Project\Journal\JournalHandler;
use App\Commands\V2\Project\MyProjects\MyProjectsCommand;
use App\Commands\V2\Project\MyProjects\MyProjectsHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;
use App\Http\Requests\Api\V2\Project\Project\JournalFilterVariants;
use App\Models\Project\Project;
use App\Services\Api\V2\Project\Service as ProjectService;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class ProjectController extends Controller
{

    public function __construct(
        private ProjectService $service,
        private CommandBusInterface $bus,
    )
    {} //Конструктор

    /**
     * @OA\Get(
     *      description="Вывести список проектов",
     *      path="/project",
     *      @OA\Response(
     *          response=200,
     *          description="Список проекто загружен успешно"
     *      )
     *  )
     */
    public function index()
    {
        return $this->service->dashboard();
    }
    
    /**
     *  ЕЖЛ
     * 
     *  @return \Illuminate\Http\Response
     */
    public function journal(int $project, JournalRequest $request)
    {
        $this->bus->addHandler(JournalCommand::class, JournalHandler::class);
        return $this->bus->dispatch(
            command: JournalCommand::class,
            input: [
                'project_id' => $project,
                'request' => $request]
        );
    } //journal

    public function getFilterVariants(int $project, JournalFilterVariants $request) //Получить варианты для фильтрации лидов в проекте
    {
        $this->bus->addHandler(GetVariantsCommand::class, GetVariantsHandler::class);
        return $this->bus->dispatch(
            command: GetVariantsCommand::class,
            input: [
                'project' => $project,
                'request' => $request,
            ]
        );
    } //getFilterVariants

    public function export(int $project, JournalRequest $request) //Выгрузка лидов в файл
    {
        $this->bus->addHandler(ExportCommand::class, ExportHandler::class);
        
        return $this->bus->dispatch(
            command: ExportCommand::class,
            input: [
                'project' => $project,
                'request' => $request,
            ]
        );
    } //export

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
