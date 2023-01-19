<?php

namespace App\Models;

use App\Events\Leads\LeadAdded;
use Illuminate\Support\Carbon;

use App\Models\Project\Project;
use App\Models\Project\Lead\Comment;
use App\Models\Project\LeadClass;

use App\Events\Leads\LeadCreated;
use App\Events\Leads\LeadExists;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    const LEAD_NEW = 'new';
    const LEAD_EXISTS = 'exists';
    const LEAD_PROCESSED = 'processed';

    const SOURCE_DIRECT_ENTRY = 'DIRECT_ENTRY';

    public const OWNER_ADDED_MANUALLY = 'Вручную';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'owner',
        'accepted_by',
        'company',
        'name',
        'surname',
        'patronymic',
        'full_name',
        'phone',
        'entries',
        'email',
        'cost',
        'comment',
        'city',
        'manual_region',
        'ip',
        'referrer',
        'source',
        'utm',
        'utm_medium',
        'utm_source',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'host',
        'url_query_string',
        'nextcall_date',
    ];

    protected $casts = [
        'utm' => 'array',
        'nextcall_date' => 'datetime:d.m.Y H:i:s',
    ];

    /**
     *      Геттеры
     */
    public function getClientName(): string
    {
        return (is_null($this->surname) ? '' : $this->surname) . $this->name . (is_null($this->patronymic) ? '' : $this->patronymic);
    } //getClientName

    public function getUtmMediumAttribute(){
        return 
            $this->utm_medium
            ??
            is_null($this->utm)
                ? null
                : (array_key_exists('utm_medium', $this->utm) ? $this->utm['utm_medium'] : null);
    } //getUtmMediumAttribute

    public function getUtmSourceAttribute(){
        return 
            $this->utm_source
            ??
            is_null($this->utm)
                ? null
                : (array_key_exists('utm_source', $this->utm) ? $this->utm['utm_source'] : null);
    } //getUtmSourceAttribute

    public function getUtmCampaignAttribute(){
        return 
            $this->utm_campaign
            ??
            is_null($this->utm)
                ? null
                : (array_key_exists('utm_campaign', $this->utm) ? $this->utm['utm_campaign'] : null);
    } //getUtmCampaignAttribute

    public function getUtmContentAttribute(){
        return
            $this->utm_content
            ??
            is_null($this->utm)
                ? null
                : (array_key_exists('utm_content', $this->utm) ? $this->utm['utm_content'] : null);
    } //getUtmContentAttribute

    public function getUtmTermAttribute(){
        return
            $this->utm_term
            ??
            is_null($this->utm)
                ? null
                : (array_key_exists('utm_term', $this->utm) ? $this->utm['utm_term'] : null);
    } //getUtmTermAttribute

    public function getFieldsAttribute() //Получить список полей лида
    {
        return $this->fillable;
    } //getFieldsAttribute

    public static function getFields() //Получить список полей лида (статический метод)
    {
        $lead = new self;
        return $lead->fields;
    } //getFields

    /**
     *      Отношения
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comment_crm(){ //Получить комментарий к лиду из CRM
        return $this->hasOne(Comment::class, 'lead_id');
    } //getCommentCRMAttribute

    public function class(){ //Получить класс лида
        return $this->belongsTo(LeadClass::class, 'class_id');
    } //class

    /**
     *      Фильтры
     */
    public function scopeFrom($query, Project|int $project)
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    public function scopeOfClass($query, int|array $class)
    {
        return is_array($class)
            ? $query->whereIn('class_id', $class)
            : $query->where('class_id', $class);
    } //scopeOfClass
    
    public function scopeName($query, string $name)
    {
        // return $query->where('full_name', $name);
        return $query->where('full_name', 'like', "%$name%");
    } //scopeName

    public function scopeOwner($query, string|array $owner)
    {
        return is_array($owner)
            ? $query->whereIn('owner', $owner)
            : $query->where('owner', $owner);
    } //scopeOwner

    public function scopePhone($query, string $phone)
    {
        return $query->where('phone', 'like', "%$phone%");
        // return is_array($phone)
        //     ? $query->whereIn('phone', $phone)
        //     : $query->where('phone', $phone);
    } //scopePhone

    public function scopeEntries($query, int $entries)
    {
        return $entries > 1
            ? $query->where('entries', '>=', 2)
            : $query->where('entries', $entries);

        // return $entries > 2
        //    ? $query->where('entries', '>', 2)
        //    : $query->where('entries', $entries);
    } //scropeEntries

    public function scopeEmail($query, string|array $email)
    {
        return is_array($email)
            ? $query->whereIn('owner', $email)
            : $query->where('owner', $email);
    } //scopeEmail
    
    public function scopeCity($query, string|array $city)
    {
        return is_array($city)
        ? $query->whereIn('city', $city)
        : $query->where('city', $city);
    } //scopeCity
    
    public function scopeCompany($query, string|array $company)
    {
        return is_array($company)
        ? $query->whereIn('company', $company)
        : $query->where('company', $company);
    } //scopeCompany
    
    public function scopeRegion($query, string|array $region)
    {
        return is_array($region)
        ? $query->whereIn('region', $region)
        : $query->where('region', $region);
    } //scopeRegion
    
    public function scopeManualRegion($query, string|array $manual_region)
    {
        return is_array($manual_region)
        ? $query->whereIn('manual_region', $manual_region)
        : $query->where('manual_region', $manual_region);
    } //scopeRegion
    
    public function scopeReferrer($query, string|array $referrer)
    {
        return is_array($referrer)
        ? $query->whereIn('referrer', $referrer)
        : $query->where('referrer', $referrer);
    } //scopeReferrer
    
    public function scopeSource($query, string|array $source)
    {
        return is_array($source)
        ? $query->whereIn('source', $source)
        : $query->where('source', $source);
    } //scopeSource
    
    public function scopeUtmMedium($query, string|array $utm_medium)
    {
        return is_array($utm_medium)
        ? $query->whereIn('utm_medium', $utm_medium)
        : $query->where('utm_medium', $utm_medium);

        //Старая версия поиска по json-поле utm (оставлено для информации)
        // ? $query->whereIn('utm->utm_medium', $utm_medium)
        // : $query->where('utm->utm_medium', $utm_medium);
    } //scopeSource
    
    public function scopeUtmSource($query, string|array $utm_source)
    {
        return is_array($utm_source)
        ? $query->whereIn('utm_source', $utm_source)
        : $query->where('utm_source', $utm_source);
    } //scopeSource
    
    public function scopeUtmCampaign($query, string|array $utm_campaign)
    {
        return is_array($utm_campaign)
        ? $query->whereIn('utm_campaign', $utm_campaign)
        : $query->where('utm_campaign', $utm_campaign);
    } //scopeUtmCampaign
    
    public function scopeUtmContent($query, string|array $utm_content)
    {
        return is_array($utm_content)
        ? $query->whereIn('utm_content', $utm_content)
        : $query->where('utm_content', $utm_content);
    } //scopeUtmContent    
    
    public function scopeUtmTerm($query, string|array $utm_term)
    {
        return is_array($utm_term)
        ? $query->whereIn('utm_term', $utm_term)
        : $query->where('utm_term', $utm_term);
    } //scopeUtmTerm    

    public function scopeHost($query, string|array $host)
    {
        return is_array($host)
            ? $query->whereIn('host', $host)
            : $query->where('host', $host);
    } //scopeHost

    public function scopeQueryString($query, string|array $query_string)
    {
        return is_array($query_string)
            ? $query->whereIn('query_string', $query_string)
            : $query->where('query_string', $query_string);
    } //scopeQueryString

    /**
     *      Рабочие методы
     */
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
        $lead = \App\Models\Leads::where([ 'project_id' => $project->id, 'phone' => $params['phone'] ])->latest()->first();

        if(is_null($lead)){ //Если лида не существует, создать новый
            return $this->createLead(array_merge($params, ['entries' => 1, 'status' => self::LEAD_NEW]));
        }

        if($project->settings['leadValidDays'] > 0)
        {
            $leadDate = Carbon::parse($lead->created_at)->addDays($project->settings['leadValidDays']);
            if(Carbon::now()->greaterThanOrEqualTo($leadDate))
                return $this->createLead(array_merge($params, ['entries' => 1, 'status' => self::LEAD_NEW]));
        }

        return $this->createLead(array_merge($params, ['entries' => $lead->entries + 1, 'status' => self::LEAD_EXISTS]));
    }

    public function createLead(array $params): Leads
    {
        $lead = Leads::create($params);
        event(new LeadAdded($lead));

        if($lead->entries === 1) //Если лид новый, сделать рассылку
            event(new LeadCreated($lead));
        else
            event(new LeadExists($lead));

        return $lead;
    } //createLead

    // public function updateLead(Leads $lead): Leads
    // {
    //     $lead->update(['entries' => $lead->entries + 1, 'status' => self::LEAD_EXISTS]);
    //     return $lead;
    // }

}
