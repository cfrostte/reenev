<?php

namespace App;

use App\Http\Traits\Utilidades;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    use Notifiable;
    use Utilidades;
    use SoftDeletes;

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'name1',
        'name2',
        'apellido1',
        'apellido2',
        'nacimiento',
        'generacion',
        'ci',
        'email',
        'password',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function realizadas()
    {
        return $this->hasMany('App\Realizada');
    }

    public function uyNacimiento($nacimiento)
    {
        return $this->uyDateFormat($nacimiento);
    }

    public function tipo($esAdmin)
    {
        if ($esAdmin) return "Admin";
        if (!$esAdmin) return "Estudiante";
        return "Indefinido";
    }
    
}