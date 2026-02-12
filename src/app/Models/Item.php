<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemImage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_ids', 
        'name',
        'brand',
        'description',
        'price',
        'condition',
        'image',
        'is_sold',      
        'buyer_id',    
        'payment_method' 
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images(){
        return $this->hasMany(ItemImage::class);
    }

    protected $casts = [
        'category_ids' => 'array',
    ];

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function order(){
        return $this->hasOne(Order::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }


}
