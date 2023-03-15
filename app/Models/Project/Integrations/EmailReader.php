<?php

namespace App\Models\Project\Integrations;

use App\Journal\Facade\Journal;
use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use stdClass;

class EmailReader extends Model
{
    protected $table = "integrations_email_readers";

    protected $fillable = [
        'user_id',
        'project_id',
        'subject',
        'email',
        'password',
        'host',
        'template',
        'enabled',
        'interval',
        'check_unseen',
        'mails_per_time',
        'mark_as_read',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'interval' => 'integer',
        'mails_per_time' => 'integer',
        'mark_as_read' => 'boolean',
    ];

    //
    //      Отношения
    //
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    } //user

    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id');
    } //project

    //
    //      Фильтры
    //
    public function scopeAddedBy($query, User|int $user)
    {
        return $query->where('user_id', $user instanceof User ? $user->id : $user);
    } //scopeAddedBy

    public function scopeFrom($query, Project|int $project)
    {
        return $query->where('project_id', $project instanceof Project ? $project->id : $project);
    } //scopeFrom

    //
    //      Рабочие методы
    //
    private function _decodeMailText($rawData, $encoding){ //Расшифровать MIME в теле письма
        switch($encoding){
            case ENC7BIT:
                return $rawData;
            case ENC8BIT:
                return quoted_printable_decode(imap_8bit($rawData));
            case ENCBINARY:
                return imap_binary($rawData);
            case ENCBASE64:
                return imap_base64($rawData);
            case ENCQUOTEDPRINTABLE:
                return quoted_printable_decode($rawData);
            case ENCOTHER:
                return $rawData;
            default:
                throw new \Exception(message: 'Не удалось расшифровать MIME в теле письма: неизвестная кодировка');
        }
    } //_decodeMailText

    private function _getTextFromMail($inbox, $index){ //Считать текст из письма
        $mailStructure = imap_fetchstructure($inbox, $index);
        switch($mailStructure->type){
            case TYPETEXT: //Если письмо типа plain-text
                return $this->_decodeMailText(imap_body($inbox, $index, FT_PEEK), $mailStructure->encoding);
                break;
            case TYPEMULTIPART: //Если письмо типа multipart (потребует большей проработки, если будет использоваться)
                if(isset($mailStructure->parts) && is_array($mailStructure->parts) && isset($mailStructure->parts[1]))
                    return $this->_decodeMailText(imap_fetchbody($inbox, $index, 2), $mailStructure->encoding);
                break;
            default:
                throw new \Exception(message: 'Ошибка: текст не принадлежит к типу plain-text или multipart');
        }
    } //_getInfoFromMail

    private function _parseMail($text) //Парсинг тела письма
    {
        //Очистка письма от лишних символов и тэгов
        $text = htmlspecialchars_decode(string: $text);
        $text = preg_replace(pattern: '/<.+?>/', replacement: PHP_EOL, subject: $text);

        // dd($text);

        preg_match(pattern: '/Имя: .+\n/', subject: $text, matches: $nameString);
        preg_match(pattern: '/Телефон:.+\n/', subject: $text, matches: $phoneString);
        preg_match(pattern: '/Город: .+\n/', subject: $text, matches: $cityString);

        $obj = new stdClass;

        $phone = str_replace(search: 'Телефон:', replace: '', subject: $phoneString[0]);
        $phone = mb_substr($phone, 1);


        $obj->name = str_replace(search: 'Имя: ', replace: '', subject: $nameString[0]);
        $obj->phone = $phone;
        $obj->city = str_replace(search: 'Город: ', replace: '', subject: $cityString[0]);

        // dd($obj);
        return $obj;
    } 

    public function getMail() //Проверить почтовый ящик и выгрузить из него письма
    {
        try{
            //Открытие почтового ящика
            $inbox = imap_open(mailbox: $this->host . 'INBOX', user: $this->email, password: $this->password);
            if(!$inbox)
                throw new \Exception(message: "Ошибка открытия почтового ящика {$this->email}: " . imap_last_error());

            //Поиск писем
            $mailIndexes = imap_search(
                imap: $inbox,
                criteria: "UNSEEN SUBJECT \"{$this->subject}\"",
                charset: 'UTF-8'
            );

            // dd($mailIndexes);

            //Парсинг полученных писем
            if($mailIndexes){
                $parsedMails = []; //Письма, прошедшие парсинг (возвращаются в виде ассоциативного массива)

                $leadRepository = app(\App\Repositories\Lead\Repository::class);

                foreach($mailIndexes as $index){
                    $text = $this->_getTextFromMail(inbox: $inbox, index: $index);
                    $data = $this->_parseMail($text);

                    $leadRepository->create(
                        project: $this->project,
                        name: $data->name,
                        phone: $data->phone,
                        host: null,
                        surname: null,
                        patronymic: null,
                        owner: null,
                        cost: null,
                        email:  null,
                        comment: null,
                        city: $data->city,
                        manual_region: null,
                        company: null,
                        ip: null,
                        referrer: null,
                        source: null,
                        utm_medium: null,
                        utm_campaign: null,
                        utm_source: null,
                        utm_term: null,
                        utm_content: null,
                        url_query_string: null,
                        nextcall_date: null
                    );
                }
            }

            imap_close($inbox);
        }
        catch(\Exception $e){
            Journal::projectError(project: $this->project, text: $e->getMessage());
        }

    } //getMail
}
