<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    /**
     * @OA\Schema(
     *     schema="Zone",
     *     description="Esquema del model zone",
     *     @OA\Property(property="name", type="string", maxLength=255, description="Nombre de la zona", example="439 Karley Loaf Suite 897"),
     *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de creació del registre"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data d'actualització del registre")
     * )
     */
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function user(){
        return $this->belongsToMany(User::class, 'users_zones', 'zone_id', 'user_id');
    }

    public function patients(){
        return $this->hasMany(Patient::class);
    }
}
