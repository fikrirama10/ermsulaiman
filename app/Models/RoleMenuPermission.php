<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenuPermission extends Model
{
    use HasFactory;

    protected $table = 'role_menu_permissions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'role_id',
        'menu_id',
        'can_view'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'role_id' => 'integer',
        'menu_id' => 'integer'
    ];

    /**
     * Get the role that owns this permission
     */
    public function role()
    {
        return $this->belongsTo(UserPrivilages::class, 'role_id');
    }

    /**
     * Get the menu that this permission refers to
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Check if role has permission to view a specific menu
     */
    public static function hasPermission($roleId, $menuId)
    {
        return self::where('role_id', $roleId)
            ->where('menu_id', $menuId)
            ->where('can_view', true)
            ->exists();
    }

    /**
     * Grant permission for role to view menu
     */
    public static function grantPermission($roleId, $menuId)
    {
        return self::updateOrCreate(
            ['role_id' => $roleId, 'menu_id' => $menuId],
            ['can_view' => true]
        );
    }

    /**
     * Revoke permission for role to view menu
     */
    public static function revokePermission($roleId, $menuId)
    {
        return self::where('role_id', $roleId)
            ->where('menu_id', $menuId)
            ->delete();
    }
}
