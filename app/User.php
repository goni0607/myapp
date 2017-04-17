<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'confirm_code', 'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'confirm_code'
    ];

    protected $casts = [
        'activated' => 'boolean',
    ];


    protected $dates = ['last_login'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function votes()
    {
        return $this->hasMany(Vote::class);
    }


    public function scopeSocialUser($query, $email)
    {
        return $query->whereEmail($email)->whereNull('password');
    }


    public function isSocialUser()
    {
        return is_null($this->password) && $this->activated;
    }


    public function isAdmin()
    {
        return ($this->email === 'triplek@triplek.net') ? true : false;
    }
}
