<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserPrivilages extends Model
{
    use HasFactory;

    protected $table = 'user_privilages';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all menus accessible by this role
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menu_permissions', 'role_id', 'menu_id')
            ->withPivot('can_view')
            ->wherePivot('can_view', true)
            ->withTimestamps();
    }
}
