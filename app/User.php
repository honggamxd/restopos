<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
  use SoftDeletes, Notifiable;
  protected $table = 'user';
  protected $dates = ['deleted_at'];
  protected $hidden = ['password','remember_token'];

  protected $fillable = [
      'username', 'password',
  ];
}
