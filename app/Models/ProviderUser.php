<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderUser extends Model
{
    protected $table = 'provider_user';
    protected $fillable = ['socialite_id', 'provider_id', 'user_id'];
}
