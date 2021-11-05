<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AdminUserRight extends Model
{
    use HasFactory, Sortable;

    protected $table = 'admin_user_rights';
    
    public $timestamps = false;
    
    protected $primaryKey = 'admin_user_rights_id';
}
