<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $connection = 'tickets';

    protected $guarded = false;

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    // (Opcional) Puedes definir la colección por defecto si quieres validaciones o conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')->useDisk('tickets');
    }
}
