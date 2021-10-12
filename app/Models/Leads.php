<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'name','surname','patronymic', 'phone', 'entries', 'email', 'cost', 'comment', 'city', 'ip', 'referrer', 'utm','host','url_query_string'
    ];

    protected $casts = [
        'utm' => 'array'
    ];

    public function getClientName(): string
    {
        return (is_null($this->surname) ? '' : $this->surname) . $this->name . (is_null($this->patronymic) ? '' : $this->patronymic);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function getEntries($phone) //Получение номера вхождений у лида
    {
        $oldLead = self::where('phone', $phone)->first();

        if(!is_null($oldLead))
            return $oldLead->entries == 1 ? 2 : 3; //2, если во второй раз, и 3, если больше двух раз

        return 1;    //Если лид не найден в базе данных, вернуть 1
    }

    public static function addToDB(array $params) //Добавить лид или обновить его количество вхождений
    {
        $entries = self::getEntries($params['phone']);
        $lead = $entries == 1 ? new self : self::where('phone', $params['phone'])->first();
        $lead->fill($params);
        $lead->entries = $entries;
        $lead->save();

        return $lead;
    }
}
