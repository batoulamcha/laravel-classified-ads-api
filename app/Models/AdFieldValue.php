<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdFieldValue extends Model
{
    use HasFactory;

    protected $fillable = ['ad_id','category_field_id','value'];

    public function ad() {
        return $this->belongsTo(Ad::class);
    }

    public function field() {
        return $this->belongsTo(CategoryField::class, 'category_field_id');
    }
}
