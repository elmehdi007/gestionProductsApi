<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model 
{
    
    use HasUuids,SoftDeletes;

    protected $primaryKey = 'role_id';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        "deleted_at"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'role_id' => 'string',
    ];

    protected function createdAt(): Attribute
    {
        $timeZone  = "UTC"; //defaut revoir ca
        if(auth()->user()) $timeZone  = TimeZone::find(auth()->user()->user_time_zone_id)->name;

        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->setTimezone($timeZone)->toISOString($timeZone),
        );
    }

    protected function updatedAt(): Attribute
    {
        $timeZone  = "UTC"; //defaut revoir ca
        if(auth()->user()) $timeZone  = TimeZone::find(auth()->user()->user_time_zone_id)->name;

        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->setTimezone($timeZone)->toISOString($timeZone),
        );
    }

    protected function deletedAt(): Attribute
    {
        $timeZone  = "UTC"; //defaut revoir ca
        if(auth()->user()) $timeZone  = TimeZone::find(auth()->user()->user_time_zone_id)->name;

        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->setTimezone($timeZone)->toISOString($timeZone),
        );
    }
}
