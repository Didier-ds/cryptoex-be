<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardletImage extends Model
{
    use HasFactory;

    protected $table = 'cardlet_images';

    protected $fillable = ['image', 'cardlet_id'];

    public function cardlet()
    {
        return $this->belongsTo(Cardlet::class);
    }
}
