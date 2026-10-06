<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descripcion
 * @property string $categoria
 * @property string $ubicacion
 * @property string $urgencia
 * @property string $estado
 * @property Carbon|null $inicia_en
 * @property Carbon|null $expira_en
 */
class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'categoria',
        'ubicacion',
        'urgencia',
        'estado',
        'inicia_en',
        'expira_en',
    ];

    protected function casts(): array
    {
        return [
            'inicia_en' => 'datetime',
            'expira_en' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estadoTemporal(): string
    {
        $ahora = Carbon::now();

        if ($this->inicia_en && $ahora->lt($this->inicia_en)) {
            return 'programada';
        }

        if ($this->expira_en && $ahora->gte($this->expira_en)) {
            return 'expirada';
        }

        return 'activa';
    }
}
