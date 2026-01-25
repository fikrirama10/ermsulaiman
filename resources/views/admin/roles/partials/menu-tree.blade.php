@foreach ($menus as $menu)
    <div class="menu-item-child mb-2">
        <div class="form-check">
            <input class="form-check-input menu-checkbox" 
                   type="checkbox" 
                   name="menu_ids[]" 
                   value="{{ $menu->id }}" 
                   id="menu_{{ $menu->id }}"
                   {{ in_array($menu->id, $assignedMenuIds) ? 'checked' : '' }}>
            <label class="form-check-label" for="menu_{{ $menu->id }}">
                @if($menu->icon)
                    <i class="{{ $menu->icon }} me-1"></i>
                @endif
                {{ $menu->name }}
            </label>
        </div>

        @if(isset($menu->childrenRecursive) && $menu->childrenRecursive->count() > 0)
            <div class="submenu-wrapper ms-4 mt-1">
                @include('admin.roles.partials.menu-tree', [
                    'menus' => $menu->childrenRecursive, 
                    'assignedMenuIds' => $assignedMenuIds,
                    'level' => ($level ?? 1) + 1
                ])
            </div>
        @endif
    </div>
@endforeach
