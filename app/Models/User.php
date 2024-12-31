<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'gender',
        'date_of_birth',
        'profile_picture',
        'role',
        'country_id',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public function getFormattedDateOfBirthAttribute()
    {return $this->date_of_birth ? $this->date_of_birth->format('d/m/Y') : null;}

    public function setPasswordAttribute($value)
    {
        if (!$value) {
            throw new \InvalidArgumentException('Password cannot be null');
        }
    
        if (\Illuminate\Support\Facades\Hash::needsRehash($value)) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
        }
    

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? asset('storage/' . $this->profile_picture)
            : asset('images/default-profile.png');
    }

    public function bookings()
    {return $this->hasMany(Booking::class, 'user_id', 'user_id');}

    public function reviews()
    {return $this->hasMany(Review::class, 'user_id', 'user_id');}

    public function notifications()
    {return $this->hasMany(Notification::class, 'user_id', 'user_id');}

    public function country()
    {return $this->belongsTo(Country::class, 'country_id', 'country_id');}

    public function scopeAdmins($query){return $query->where('role', 'admin');}
    public function scopeCustomer($query){return $query->where('role', 'customer');}
    public function scopeGuests($query){return $query->where('role', 'guest');}
    public function scopeByGender($query, $gender){return $query->where('gender', $gender);}

    public function isAdmin(){return $this->role === 'admin';}
    public function isCustomer(){return $this->role === 'customer';}
    public function isGuest(){return $this->role === 'guest';}
}
