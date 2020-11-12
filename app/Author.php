<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Author extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'id_author');
    }

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getDeletedAtAttribute(){
        if(!is_null($this->attributes['deleted_at'])){
            return Carbon::parse($this->attributes['deleted_at'])->format('Y-m-d H:i:s');
        }
    }
}
