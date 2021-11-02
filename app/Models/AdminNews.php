<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AdminNews extends Model
{
    use HasFactory, Sortable;

    protected $table = 'admin_news';
    
    public $timestamps = false;
    
    protected $primaryKey = 'news_id';
    
    protected $fillable = ['updated_at'];
    
    public function getStatusAttribute($value) {
		$status = $this->is_show_on_student_top == 0 ? "非表示" : "表示";
		return $status;
    }
    public function getStatusClassAttribute($value) {
        $status = $this->is_show_on_student_top == 0 ? "fa-toggle-on" : "fa fa-toggle-off";
        return $status;
    }
    public function newsSubject() 
    {
    	return $this->belongsTo(NewsSubject::class, 'news_subject_id');
    }
    public function adminNewsInfo() 
    {
        return $this->hasMany(AdminNewsInfo::class, 'news_id');
    }
}
