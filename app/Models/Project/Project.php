<?php

namespace App\Models\Project;

use App\Models\User;
use App\Models\Leads;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'host',
        'user_id',
        'settings',
        'api_token'
    ];

    //Настройки по умолчанию
    protected $attributes = [
        'settings' =>
        '{
            "email":
            {
                "enabled": true,
                "fields": []
            },

            "telegram":
            {
                "enabled": true,
                "fields": []
            },

            "timezone": "UTC"
        }',
    ];

    protected $casts = ['settings' => 'array'];

    public function isOwner(): bool
    {
        return Project::findOrFail($this->id)->user_id === Auth::id();
    }

    public function hosts()
    {
        return $this->hasMany(Host::class);
    }

    public function user_permissions()
    {
        return $this->hasMany(UserPermissions::class);
    }

    public function emails() //Получить все e-mail адреса рассылки
    {
        return $this->hasMany(Email::class);
    }

    public function getTelegramChannelIdAttribute() //Получить идентификатор канала, на который назначен проект
    {
        return TelegramID::where(['project_id' => $this->id, 'type' => TelegramID::TYPE_CHANNEL])->first();
    } //getTelegramChannelIdAttribute

    public function getTelegramPrivateIdsAttribute() //Получить всех подписчиков личной рассылки проекта
    {
        return TelegramID::where(['project_id' => $this->id, 'type' => TelegramID::TYPE_PRIVATE, 'approved' => true])->get();
    } //getTelegramPrivateIdsAttribute

    public function getTimezoneAttribute() //Аксессор для удобного доступа к часовому поясу
    {
        return $this->settings['timezone'];
    } //getTimezoneAttribute

    public function setTimezoneAttribute(string $value) //Мутатор для удобного изменения часового пояса
    {
        $this->settings['timezone'] = $value;
    } //setTimezoneAttribute

    public function leads()
    {
        return $this->hasMany(Leads::class, 'project_id');
    }

    public function leadsToday()
    {
        return $this->hasMany(Leads::class, 'project_id')->whereDate('created_at', Carbon::today());
    }

    /**
     * Return the user project
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classes(){ //Получить классы, назначенные проекту
        return $this->hasMany(LeadClass::class);
    } //classes
}