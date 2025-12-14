<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFieldOption extends Model
{
    protected $fillable = ['category_field_id','value'];

    public function field() {
        return $this->belongsTo(CategoryField::class, 'category_field_id');
    }
}
