<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Sortable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'email', 'email_verified_at', 'last_login_at', 'remember_token', 'remember_token_expires_at', 'created_at', 'updated_at'];
}
