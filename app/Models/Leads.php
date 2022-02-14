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

    public static function getEntries($projectId, $phone) //Получение номера вхождений у лида
    {
        $oldLead = self::where('project_id', $projectId)->where('phone', $phone)->count();

        if ($oldLead === 0) {
            return 1;
        }

        if ($oldLead === 1) {
            return 2 ;
        }

        if ($oldLead > 1) {
            return ++ self::where('project_id', $projectId)->where('phone', $phone)->where('entries', '>', 1)->first()->entries ;
        }
    }

    public static function addToDB(array $params) //Добавить лид или обновить его количество вхождений
    {
        $project = Project::find($params['project_id']);
        
        //Проверка существования лида
        $lead = \App\Models\Leads::where([ 'project_id' => $project->id, 'phone' => $params['phone'] ])->orderBy('updated_at', 'desc')->first();
        if(is_null($lead)){ //Если такого лида нет, создать его
            $lead = new Leads;
            $lead->fill($params);
            $lead->entries = 1;
            $lead->status = self::LEAD_NEW;
            $lead->save();

            event(new LeadCreated($lead));
            return $lead;
        }
        else{ //Если лид существует, проверяет срок годности лида
            if($project->settings['leadValidDays'] > 0){ //Если установлен срок годности лида
                //Если срок годности лида истёк, создать новый лид
                if( Carbon::parse($lead->updated_at)->addDays($project->settings['leadValidDays'])->lessThanOrEqualTo(Carbon::now()) ){
                    $newLead = new Leads;
                    $newLead->fill($params);
                    $newLead->entries = 1;
                    $newLead->status = self::LEAD_NEW;
                    $newLead->save();

                    event(new LeadCreated($newLead));
                    return $newLead;
                }
                else{ //Если срок годности ещё не истёк, увеличить у старого лида поле entries
                    $lead->entries += 1;
                    $lead->status = self::LEAD_EXISTS;
                    $lead->save();
                    return $lead;
                }
            }
            else{ //Если срок годности лида не установлен, создать новый лид
                $newLead = new Leads;
                $newLead->fill($params);
                $newLead->entries = 1;
                $newLead->status = self::LEAD_NEW;
                $newLead->save();

                event(new LeadCreated($newLead));
                return $newLead;
            }
        }
    }
}
