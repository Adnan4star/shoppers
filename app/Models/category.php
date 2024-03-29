<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['name','slug','image','status','showHome'];


    public function sub_category()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function NoOfProducts()
    {
        return $this->hasMany(Product::class);
    }
}
