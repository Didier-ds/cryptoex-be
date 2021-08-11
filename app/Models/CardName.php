<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardName extends Model
{
    use HasFactory;
    protected $table = 'cards_name';
    protected $fillable = [
        'name'
    ];
}
