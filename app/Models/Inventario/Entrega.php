<?php

namespace App\Models\Inventario;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $connection = 'inventario';

    protected $guarded = [];

    public function elemento()
    {
        return $this->belongsTo(Elemento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
