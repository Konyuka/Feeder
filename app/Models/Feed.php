<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    protected $fillable=['feed_link','category_id', 'source_id','subscribed','subscription_id'];

    public function source(){
        return $this->belongsTo(Source::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
