<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Category extends Model
{
    use HasFactory, Sortable;

    protected $table = 'category';
    
    public $timestamps = false;
    
    protected $primaryKey = 'category_id';
    
    public function courses()
    {
        return $this->belongsToMany('App\Models\course', 'category_course' ,
            'category_id', 'course_id', 'category_id', 'course_id');
    }

    public function category_infos() {
        return $this->hasMany('App\Models\CategoryInfo', 'category_id', 'category_id');
    }
}
