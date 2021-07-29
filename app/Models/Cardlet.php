<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cardlet extends Model
{
    use HasFactory;

    protected $table = 'cards';
    protected $fillable = [
        'uuid',
        'name',
        'type',
        'rate',
        'code',
        'comment',
        'image'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
