<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'user_id',
    ];

    // Relationship to the user who created the product
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
