<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{ 
    use SoftDeletes; 
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'bornDate',
        'email',
        'phone',
        'address',
        'docType',
        'numDoc',
        'userType',
        'status',
        'disabledStartDate',
        'disabledReason',
        'weekdayStartWorkHours',
        'weekdayEndWorkHours',
        'startEmploymentDate',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return $this->lastName . ', ' . $this->firstName;
    }

    public function getUserTypeFormatted()
    {
        switch ($this->userType) {
            case 'Receptionist':
                return 'Recepcionista';
            case 'Cleaner':
                return 'Limpieza';
            case 'Guest':
                return 'Huésped';
            default:
                return $this->userType;
        }
    }

    public function getFormattedPhoneAttribute()
    {
        // Obtenemos el número de teléfono
        $phone = $this->attributes['phone'];

        // Si el número de teléfono no tiene 13 caracteres, no se puede formatear correctamente
        if (strlen($phone) !== 13) {
            return $phone;
        }

        // Formateamos el número de teléfono
        $formattedPhone = '+' . substr($phone, 0, 2) . ' ' . substr($phone, 2, 1) . ' ' . substr($phone, 3, 4) . ' ' . substr($phone, 7);

        return $formattedPhone;
    }

    public function cleanings()
    {
        return $this->hasMany(Cleaning::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

}
