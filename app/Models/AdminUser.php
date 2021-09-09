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
    protected $table = 'admin_user';
    protected $primaryKey = 'admin_user_id';

    /**
     * @var array
     */
    public $timestamps = false;
    protected $fillable = ['admin_user_id', 'admin_user_name', 'admin_user_email', 'admin_user_description', 'last_login_date', 'is_sercurity', 'is_join_contact', 'is_online', 'brand_id'];
}
