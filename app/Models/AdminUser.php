<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;

class AdminUser extends Authenticatable
{
    use HasFactory, Sortable;

    /**
     * The table associated with the model.
     *
     * @var string
     */

    /**
     * @var array
     */
    protected $fillable = ['id', 'admin_name', 'admin_email', 'description', 'last_login_at', 'remember_token', 'is_join_contact', 'is_online'];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
