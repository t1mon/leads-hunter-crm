<?php

namespace App\Models\Project\Integrations;

use App\Models\Leads;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Utils\Json;

class Mango extends Model
{
    use HasFactory;

    protected $table = "integrations_mango";

    protected $fillable = [
        'name',
        'project_id',
        'vpbx_api_key',
        'vpbx_api_salt',
        'enabled',
    ];

    protected $attributes = [
        'enabled' => true,
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];


    public const API_URL = 'https://app.mango-office.ru/vpbx/ab/contacts/create/'; //Адрес, на который будут отправляться лиды
    
    private const TEMPLATE = [ //Заготовка тела запроса
        'data' => [
            [
                'type' => 0,
                'name' => '',
                'comment' => 'Контакт с Leads Hunter CRM',
                'phones' => [
                    [
                        'type' => 1, //Мобильный телефон
                        'phone' => '',
                        'is_default' => true, //Главный телефон в группе
                    ]
                ],
                'emails' => [
                    [
                        'email' => '',
                        'is_default' => true,
                    ],
                ]
            ]
        ]
    ];

    //
    //  Геттеры
    //
    public function sign(string $json): string
    {
        return hash(algo: 'sha256', data: $this->vpbx_api_key . $json . $this->vpbx_api_salt);
    } //sign

    public function json(Leads $lead): string //Сгенерировать json-строку для запроса на основе данных лида
    {
        $data = self::TEMPLATE;
        Arr::set(array: $data, key: 'data.0.name', value: $lead->getClientName());
        Arr::set(array: $data, key: 'data.0.phones.0.phone', value: Str::replaceFirst(search: '7', replace: '8', subject: $lead->phone));
        Arr::set(array: $data, key: 'data.0.emails.0.email', value: $lead->email ?? 'example@mail.com');

        // return Json::encode(value: $data);
        return json_encode(value: $data);
    } //json

    //
    //  Отношения
    //
    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    //
    //  Служебные методы
    //
    public function sendLead(Leads $lead) //Отправить лид в MANFO OFFICE
    {
        $json = $this->json($lead);

        $data = [
            'vpbx_api_key' => $this->vpbx_api_key,
            'json' => $json,
            'sign' => $this->sign($json),
        ];

        $response = Http::asForm()->post(url: self::API_URL, data: $data);

        return $response;
    } //sendLead
}
