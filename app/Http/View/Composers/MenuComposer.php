<?php

namespace App\Http\View\Composers;

use App\Services\MenuService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MenuComposer
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $userMenus = collect([]);

        if (Auth::check()) {
            $userMenus = $this->menuService->getUserMenus(Auth::id());
        }

        $view->with('userMenus', $userMenus);
    }
}
