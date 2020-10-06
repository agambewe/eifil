<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'description', 
        'author_name',
        'hastag'
    ];

    public function detailAuthor(){
        return $this->belongsTo(Author::class, 'id_author', 'id');
    }

    public function detailCategory(){
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }
}
