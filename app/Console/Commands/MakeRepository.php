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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {   
        //Загрузка данных из консоли
        $this->_load();

        //Проверка существования данных
        if($this->_checkIfExists()){
            $this->error('Репозиторий ' . $this->_savePath() . ' уже существует');
            return 1;
        }

        if($this->_make()){
            $this->info('Создан репозиторий ' . $this->_savePath());
            return 0;
        }
        else{
            $this->info('Ошибка создания ' . $this->_savePath());
            return 1;
        }
    }

    //
    //  Служебные методы
    //
    private function _checkIfExists(): bool
    {
        return Storage::disk('repositories')->exists($this->_savePath());
    } //_checkIfExists

    private function _load(): void
    {
        $this->type = in_array(needle: $this->option('type'), haystack: [self::TYPE_CUD, self::TYPE_READ]) ? $this->option('type') : self::TYPE_CUD;
        $this->namespace = Str::replace(search: '/', replace: '\\', subject: $this->argument('namespace'));
        $this->fullModel = Str::of($this->option('model'))->replace(search: '/', replace: '\\');
        $this->shortModel = Str::of($this->fullModel)->explode('\\')->last();
        $this->variable = Str::of($this->shortModel)->camel();
    } //_load

    private function _stubPath(): string
    {
        if($this->type === self::TYPE_READ)
            return 'Repositories/ReadRepository.stub';
        else
            return 'Repositories/Repository.stub';
    } //_stubPath

    private function _loadStub(): string
    {
        return Storage::disk('stubs')->get( $this->_stubPath() );
    } //_loadStub

    private function _savePath(): string
    {
        $name = $this->type === self::TYPE_READ ? 'ReadRepository.php' : 'Repository.php';
        return Str::of($this->namespace)->replace(search: '\\', replace: '/')->append('/')->append($name);
    } //_savePath

    private function _make(): int
    {
        $file = $this->_loadStub();

        //Подстановка переменных
        $file = Str::replace(search: '$NAMESPACE$', replace: $this->namespace, subject: $file);
        $file = Str::replace(search: '$FULL_MODEL$', replace: $this->fullModel, subject: $file);
        $file = Str::replace(search: '$SHORT_MODEL$', replace: $this->shortModel, subject: $file);
        $file = Str::replace(search: '$variable$', replace: $this->variable, subject: $file);

        //Создание директории
        if(!Storage::disk('repositories')->makeDirectory($this->namespace))
            return 0;

        //Сохранение файла
        return Storage::disk('repositories')->put(path: $this->_savePath(), contents: $file) ? 1 : 0;
    } //_makeCUD
}
