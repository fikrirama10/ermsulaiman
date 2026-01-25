<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use App\Models\UserPrivilages;
use App\Models\RoleMenuPermission;
use Illuminate\Support\Collection;

class MenuService
{
    /**
     * Get all menus accessible by a user
     * 
     * @param int $userId
     * @return Collection
     */
    public function getUserMenus($userId)
    {
        $user = User::find($userId);

        if (!$user || !$user->idpriv) {
            return collect([]);
        }

        // Get role's accessible menus
        $role = UserPrivilages::with(['menus' => function ($query) {
            $query->active()->ordered()->with(['children' => function ($q) {
                $q->active()->ordered();
            }]);
        }])->find($user->idpriv);

        if (!$role) {
            return collect([]);
        }

        // Get only root menus (parent_id is null)
        $rootMenus = $role->menus()->rootMenus()->get();

        // Build hierarchical structure
        return $this->buildMenuTree($rootMenus, $user->idpriv);
    }

    /**
     * Build hierarchical menu tree
     * 
     * @param Collection $menus
     * @param int $roleId
     * @return Collection
     */
    protected function buildMenuTree(Collection $menus, $roleId)
    {
        return $menus->map(function ($menu) use ($roleId) {
            // Get accessible children for this role
            $accessibleChildren = $menu->children()
                ->whereHas('roles', function ($query) use ($roleId) {
                    $query->where('role_id', $roleId)
                        ->where('can_view', true);
                })
                ->active()
                ->ordered()
                ->get();

            if ($accessibleChildren->isNotEmpty()) {
                $menu->accessibleChildren = $this->buildMenuTree($accessibleChildren, $roleId);
            }

            return $menu;
        });
    }

    /**
     * Check if user can access a specific menu
     * 
     * @param int $userId
     * @param string $menuSlug
     * @return bool
     */
    public function canAccessMenu($userId, $menuSlug)
    {
        $user = User::find($userId);

        if (!$user || !$user->idpriv) {
            return false;
        }

        $menu = Menu::where('slug', $menuSlug)->first();

        if (!$menu) {
            return false;
        }

        return RoleMenuPermission::hasPermission($user->idpriv, $menu->id);
    }

    /**
     * Get all menus in hierarchical structure
     * 
     * @return Collection
     */
    public function getAllMenusHierarchical()
    {
        $rootMenus = Menu::active()->rootMenus()->ordered()->get();

        return $rootMenus->map(function ($menu) {
            $menu->childrenRecursive = $this->getChildrenRecursive($menu);
            return $menu;
        });
    }

    /**
     * Get children recursively
     * 
     * @param Menu $menu
     * @return Collection
     */
    protected function getChildrenRecursive(Menu $menu)
    {
        $children = $menu->children()->active()->ordered()->get();

        if ($children->isEmpty()) {
            return collect([]);
        }

        return $children->map(function ($child) {
            $child->childrenRecursive = $this->getChildrenRecursive($child);
            return $child;
        });
    }

    /**
     * Get menus for a specific role
     * 
     * @param int $roleId
     * @return Collection
     */
    public function getMenusForRole($roleId)
    {
        $role = UserPrivilages::find($roleId);

        if (!$role) {
            return collect([]);
        }

        return $role->menus()->active()->ordered()->get();
    }

    /**
     * Sync menus for a role
     * 
     * @param int $roleId
     * @param array $menuIds
     * @return void
     */
    public function syncRoleMenus($roleId, array $menuIds)
    {
        // Delete existing permissions
        RoleMenuPermission::where('role_id', $roleId)->delete();

        // Create new permissions
        foreach ($menuIds as $menuId) {
            RoleMenuPermission::create([
                'role_id' => $roleId,
                'menu_id' => $menuId,
                'can_view' => true
            ]);
        }
    }
}
