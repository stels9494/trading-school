<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'patronymic',
        'email',
        'login',
        'password',
        'command_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'fio'
    ];

    /********** RELATIONSHIPS START ********************/

        /**
         * Команда, в которой находится пользователь
         *
         */
        public function command()
        {
            return $this->belongsTo(Command::class);
        }

    /********** RELATIONSHIPS FINISH ********************/

    /**
     * установка капитана команды
     */
    public function setCommanderAttribute(bool $commander = false): void
    {
        if ($commander)
        {
            $this->assignRole('commander')->removeRole('member');
        } else {
            $this->assignRole('member')->removeRole('commander');
        }

        return ;
    }

    public function getFioAttribute()
    {
        return $this->lastname.' '.$this->firstname.' '.$this->patronymic;
    }

    /**
     * Имеет ли пользователь роль капитана
     *
     * @return true если капитан в указанной команде
     */
    public function getCommanderAttribute(): bool
    {
        return $this->hasRole('commander');
    }
}
