<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {namespace : Место создания файла} {--model= Модель, для которой создается репозиторий} {--type=cud : Тип репозитория (cud или read)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать класс-репозиторий для модели';

    //Типы репозитория
    public const TYPE_CUD = 'cud';
    public const TYPE_READ = 'read';

    protected string $type;
    protected string $namespace;
    protected string $fullModel; //Полное имя модели с пространством модели
    protected string $shortModel; //Краткое имя модели (только имя класса)
    protected string $variable; //Имя модели в нижнем регистре, которое будет использоваться в качестве переменной в методах


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // $this->type = Arr::hasAny(array: [self::TYPE_CUD, self::TYPE_READ], keys: $this->option('type')) ? $this->argument('type') : self::TYPE_CUD;
        // $this->namespace = $this->argument('namespace');
        // $this->fullModel = $this->option('model');
        // $this->shortModel = Str::of($this->fullModel)->explode('/')->last();
        // $this->variable = Str::of('shortModel')->lower()->snake();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->type === self::TYPE_CUD)
            return $this->_makeCUD();
        elseif($this->type === self::TYPE_READ)
            return $this->_makeRead();
        else
            return 0;
    }

    //
    //  Служебные методы
    //
    private function _stubPath(): string{
        if($this->type === self::TYPE_READ)
            return 'Repositories/ReadRepository.stub';
        else
            return 'Repositories/Repository.stub';
    } //_stubPath

    private function _loadStub(){
        return Storage::disk('stubs')->get( $this->_stubPath() );
    } //_loadStub

    private function _makeCUD(): int{
        return 1;
    } //_makeCUD

    private function _makeRead(): int{
        return 1;
    } //_makeRead
}
