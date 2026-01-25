@extends('layouts.index')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Atur Menu untuk Role: {{ $role->privilages }}
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ url('dashboard') }}" class="text-muted text-hover-primary">Menu</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.roles.index') }}" class="text-muted text-hover-primary">Role & Menu</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Atur Menu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            
            @if ($message = session('berhasil'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($message = session('gagal'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Menu yang dapat diakses oleh role: <strong>{{ $role->privilages }}</strong></h3>
                </div>
                <form action="{{ route('admin.roles.update-menus', $role->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        <div class="mb-4">
                            <button type="button" id="checkAll" class="btn btn-sm btn-primary me-2">
                                <i class="bi bi-check-square"></i> Pilih Semua
                            </button>
                            <button type="button" id="uncheckAll" class="btn btn-sm btn-secondary">
                                <i class="bi bi-square"></i> Hapus Semua
                            </button>
                        </div>

                        <div class="menu-tree">
                            @foreach ($allMenus as $menu)
                                <div class="menu-item-wrapper mb-3 p-3 border rounded">
                                    <div class="form-check">
                                        <input class="form-check-input menu-checkbox parent-menu" 
                                               type="checkbox" 
                                               name="menu_ids[]" 
                                               value="{{ $menu->id }}" 
                                               id="menu_{{ $menu->id }}"
                                               {{ in_array($menu->id, $assignedMenuIds) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="menu_{{ $menu->id }}">
                                            @if($menu->icon)
                                                <i class="{{ $menu->icon }} me-2"></i>
                                            @endif
                                            {{ $menu->name }}
                                        </label>
                                    </div>

                                    @if(isset($menu->childrenRecursive) && $menu->childrenRecursive->count() > 0)
                                        <div class="submenu-wrapper ms-4 mt-2">
                                            @include('admin.roles.partials.menu-tree', [
                                                'menus' => $menu->childrenRecursive, 
                                                'assignedMenuIds' => $assignedMenuIds,
                                                'level' => 1
                                            ])
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!--end::Content-->
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Check all checkboxes
        $('#checkAll').click(function() {
            $('.menu-checkbox').prop('checked', true);
        });

        // Uncheck all checkboxes
        $('#uncheckAll').click(function() {
            $('.menu-checkbox').prop('checked', false);
        });

        // Parent checkbox logic: check/uncheck children
        $('.parent-menu').change(function() {
            $(this).closest('.menu-item-wrapper').find('.submenu-wrapper .menu-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Child checkbox logic: update parent if all children unchecked
        $('.submenu-wrapper .menu-checkbox').change(function() {
            var parent = $(this).closest('.menu-item-wrapper').find('> .form-check .parent-menu');
            var siblings = $(this).closest('.submenu-wrapper').find('.menu-checkbox');
            
            if ($(this).prop('checked')) {
                parent.prop('checked', true);
            } else {
                var anyChecked = false;
                siblings.each(function() {
                    if ($(this).prop('checked')) {
                        anyChecked = true;
                        return false;
                    }
                });
                if (!anyChecked) {
                    parent.prop('checked', false);
                }
            }
        });
    });
</script>
@endsection
