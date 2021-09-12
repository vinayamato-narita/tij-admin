<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminUser extends Authenticatable
{
    use HasFactory, Sortable;
    public function getAuthPassword()
    {
        return $this->admin_user_password;
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user';


    /**
     * @var array
     */
    public $timestamps = false;
    protected $fillable = ['admin_user_id', 'admin_user_name', 'admin_user_email', 'admin_user_password', 'last_login_date', 'remember_token', 'remember_token_expires_at', 'created_at', 'updated_at'];
}
