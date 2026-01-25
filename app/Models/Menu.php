<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slug',
        'url',
        'icon',
        'parent_id',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
        'parent_id' => 'integer'
    ];

    /**
     * Parent menu relationship (for submenus)
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Children menus relationship (submenus)
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order_index');
    }

    /**
     * Roles that have access to this menu
     */
    public function roles()
    {
        return $this->belongsToMany(UserPrivilages::class, 'role_menu_permissions', 'menu_id', 'role_id')
            ->withPivot('can_view')
            ->withTimestamps();
    }

    /**
     * Scope for active menus only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root menus (no parent)
     */
    public function scopeRootMenus($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for ordered menus
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    /**
     * Check if menu has children
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all descendant menus recursively
     */
    public function getAllChildren()
    {
        $children = collect([]);

        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }

        return $children;
    }
}
