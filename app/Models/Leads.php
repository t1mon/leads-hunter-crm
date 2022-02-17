<?php

namespace App\Models;

use Illuminate\Support\Carbon;

use App\Models\Project\Project;
use App\Models\Project\Lead\Comment;
use App\Models\Project\LeadClass;

use App\Events\Leads\LeadCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    const LEAD_NEW = 'new';
    const LEAD_EXISTS = 'exists';
    const LEAD_PROCESSED = 'processed';

    const SOURCE_DIRECT_ENTRY = 'DIRECT_ENTRY';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'owner', 'name','surname','patronymic', 'phone', 'entries', 'email', 'cost', 'comment', 'city', 'ip', 'referrer', 'source', 'utm','host','url_query_string'
    ];

    protected $casts = [
        'utm' => 'array'
    ];

    public function getClientName(): string
    {
        return (is_null($this->surname) ? '' : $this->surname) . $this->name . (is_null($this->patronymic) ? '' : $this->patronymic);
    }

    public function getUtmMediumAttribute(){
        return is_null($this->utm)
                ? ''
                : (array_key_exists('utm_medium', $this->utm) ? $this->utm['utm_medium'] : '');
    }

    public function getUtmSourceAttribute(){
        return is_null($this->utm)
                ? ''
                : (array_key_exists('utm_source', $this->utm) ? $this->utm['utm_source'] : '');
    }

    public function getUtmCampaignAttribute(){
        return is_null($this->utm)
                ? ''
                : (array_key_exists('utm_campaign', $this->utm) ? $this->utm['utm_campaign'] : '');
    }

    public function getUtmContentAttribute(){
        return is_null($this->utm)
                ? ''
                : (array_key_exists('utm_content', $this->utm) ? $this->utm['utm_content'] : '');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comment_CRM(){ //Получить комментарий к лиду из CRM
        return $this->hasOne(Comment::class, 'lead_id');
    } //getCommentCRMAttribute

    public function class(){ //Получить класс лида
        return $this->belongsTo(LeadClass::class, 'class_id');
    } //class


    public static function addToDB(array $params) //Добавить лид или обновить его количество вхождений
    {
        $project = Project::find($params['project_id']);

        //Проверка существования лида
        $lead = \App\Models\Leads::where([ 'project_id' => $project->id, 'phone' => $params['phone'] ])->orderBy('updated_at', 'desc')->first();

        if(is_null($lead)){ //Если лида не существует, создать новый
            $lead = Leads::create(array_merge($params, ['entries' => 1, 'status' => self::LEAD_NEW]));
            event(new LeadCreated($lead));
        }
        else{ //Если лид существует
            if($project->settings['leadValidDays'] > 0){ //Если выставлен срок годности лида
                // Если срок годности лда уже истёк, создать новый лид
                if( Carbon::parse($lead->updated_at)->addDays($project->settings['leadValidDays'])->lessThanOrEqualTo(Carbon::now()) ){
                    $lead = Leads::create(array_merge($params, ['entries' => 1, 'status' => self::LEAD_NEW]));
                    event(new LeadCreated($lead));
                }
                else //Если срок годности не истёк, увеличить количество вхождений лида
                    $lead->update(['entries' => $lead->entries + 1, 'status' => self::LEAD_EXISTS]);
            }
            else //Если срок не выставлен, просто увеличить количество вхождений лида
                $lead->update(['entries' => $lead->entries + 1, 'status' => self::LEAD_EXISTS]);
        }

        return $lead;
    }

    public function createOrUpdate(array $params): Leads //Добавить лид или обновить его количество вхождений
    {
        $project = Project::find($params['project_id']);

        //Проверка существования лида
        $lead = \App\Models\Leads::where([ 'project_id' => $project->id, 'phone' => $params['phone'] ])->orderBy('updated_at', 'desc')->first();

        if(is_null($lead)){ //Если лида не существует, создать новый
            return $this->createLead(array_merge($params, ['entries' => 1, 'status' => self::LEAD_NEW]));
        }

        if($project->settings['leadValidDays'] > 0 && Carbon::parse($lead->updated_at)->addDays($project->settings['leadValidDays'])->lessThanOrEqualTo(Carbon::now()))
        {
            return $this->createLead(array_merge($params, ['entries' => 1, 'status' => self::LEAD_NEW]));
        }

        return $this->createLead(array_merge($params, ['entries' => $lead->entries + 1, 'status' => self::LEAD_EXISTS]));
    }

    public function createLead(array $params): Leads
    {
        $lead = Leads::create($params);
        if($lead->entries === 1) //Если лид новый, сделать рассылку
            event(new LeadCreated($lead));

        return $lead;
    } //createLead

    // public function updateLead(Leads $lead): Leads
    // {
    //     $lead->update(['entries' => $lead->entries + 1, 'status' => self::LEAD_EXISTS]);
    //     return $lead;
    // }

}
