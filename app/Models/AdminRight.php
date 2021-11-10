<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AdminRight extends Model
{
    use HasFactory, Sortable;

    protected $table = 'admin_rights';
    
    public $timestamps = false;
    
    protected $primaryKey = 'admin_rights_id';
    
    public function adminUserRights() 
    {
        return $this->BelongsTo(AdminUserRight::class, 'admin_rights_id', 'admin_rights_id');
    }
}
