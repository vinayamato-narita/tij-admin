<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;

class AdminUser extends Authenticatable
{
    use HasFactory, Sortable;
    
    protected $table = 'admin_user';

    public $timestamps = false;

    protected $primaryKey = 'admin_user_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */

    /**
     * @var array
     */
    protected $fillable = ['admin_user_id', 'admin_user_name', 'admin_user_email', 'admin_user_description', 'last_login_date', 'is_join_contact', 'is_online'];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
