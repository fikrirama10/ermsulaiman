<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\UserPrivilages;
use App\Models\RoleMenuPermission;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleMenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
        $this->middleware('auth');
    }

    /**
     * Display list of all roles
     */
    public function index()
    {
        $roles = UserPrivilages::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show menu assignment interface for a specific role
     */
    public function editMenus($roleId)
    {
        $role = UserPrivilages::findOrFail($roleId);

        // Get all menus hierarchically
        $allMenus = $this->menuService->getAllMenusHierarchical();

        // Get currently assigned menu IDs for this role
        $assignedMenuIds = RoleMenuPermission::where('role_id', $roleId)
            ->where('can_view', true)
            ->pluck('menu_id')
            ->toArray();

        return view('admin.roles.edit-menus', compact('role', 'allMenus', 'assignedMenuIds'));
    }

    /**
     * Update menu permissions for a role
     */
    public function updateMenus(Request $request, $roleId)
    {
        $request->validate([
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'exists:menus,id'
        ]);

        $role = UserPrivilages::findOrFail($roleId);

        try {
            DB::beginTransaction();

            $menuIds = $request->input('menu_ids', []);
            $this->menuService->syncRoleMenus($roleId, $menuIds);

            DB::commit();

            return redirect()
                ->route('admin.roles.edit-menus', $roleId)
                ->with('berhasil', 'Menu permissions updated successfully for role: ' . $role->privilages);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('gagal', 'Failed to update menu permissions: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint to get menus for a specific role (AJAX)
     */
    public function getMenusForRole($roleId)
    {
        $menus = $this->menuService->getMenusForRole($roleId);

        return response()->json([
            'success' => true,
            'menus' => $menus
        ]);
    }

    /**
     * API endpoint to get all menus
     */
    public function getAllMenus()
    {
        $menus = $this->menuService->getAllMenusHierarchical();

        return response()->json([
            'success' => true,
            'menus' => $menus
        ]);
    }
}
