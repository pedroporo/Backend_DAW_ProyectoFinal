<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Alert extends Model
{
    protected $table = 'alerts';
       /**
     * @OA\Schema(
     *     schema="Alert",
     *     description="Esquema del model Alert",
     *     @OA\Property(
     *         property="start_date",
     *         type="date",
     *         format="date",
     *         description="Data de inicio de la alarma.",
     *         example="15-05-2002"
     *     ),
     *     @OA\Property(
     *         property="end_date",
     *         type="date",
     *         format="date",
     *         description="Data de finalizacion de la alarma.",
     *         example="15-05-2002"
     *     ),
     *     @OA\Property(property="zone_id", type="integer", description="Id de la zona"),
     *     @OA\Property(property="type", type="string", description="Tipos permitidos",enum={\App\Enums\Alarms_type::class}),
     *     @OA\Property(property="description", type="string", description="Descripcion de la alarma")
     * )
     */
    use HasFactory;
    protected $fillable = [
        'zone_id',
        'type',
        'start_date',
        'end_date',
        'weekday',
        'description'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function zone(){
        return $this->belongsTo(Zone::class);
    }
}
