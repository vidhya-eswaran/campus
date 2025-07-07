<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ModulePermission;
use App\Models\User;

class PermissionController extends Controller
{
    /**
     * Get permissions with their children
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function index(Request $request)
    {
        $role = $request->query('role');
        $userId = $request->query('user_id');
        
        try {
            // Build the base query
            $query = DB::table('module_permissions')
                ->select(
                    'id',
                    'label',
                    'selected',
                    'select_all as selectAll',
                    'view_permission as view',
                    'create_permission as create',
                    'edit_permission as edit',
                    'delete_permission as delete',
                    'menu_key as key',
                    'role',
                    'parent_id'
                );
            
            // Apply filters
            // if ($userId) {
            //     // Assuming there's a user_permissions table linking users to permissions
            //     $query->join('user_permissions', 'module_permissions.id', '=', 'user_permissions.permission_id')
            //           ->where('user_permissions.user_id', $userId);
            //           $query->where('role', $role);
            // } 
            if ($userId) {
                $user = User::find($userId);
                if (!$user) {
                    return response()->json(['error' => 'User not found'], 404);
                }
                // filter by user's role
                $query->where('role', $user->user_type);
            }
            elseif ($role) {
                $query->where('role', $role);
            }
            
            // Get all permissions
            $allPermissions = $query->get();
            
            // Group permissions by parent_id
            $permissionsByParent = [];
            foreach ($allPermissions as $permission) {
                $parentId = $permission->parent_id;
                if (!isset($permissionsByParent[$parentId])) {
                    $permissionsByParent[$parentId] = [];
                }
                $permissionsByParent[$parentId][] = $permission;
            }
            
            // Get root permissions (those with null parent_id)
            $rootPermissions = $permissionsByParent[null] ?? [];
            
            // Add children to each root permission and convert boolean-like fields to true/false
            foreach ($rootPermissions as $key => $rootPermission) {
                $rootPermission->children = $permissionsByParent[$rootPermission->id] ?? [];
                
                // Convert to true/false for boolean-like fields
                $rootPermission->selected = (bool) $rootPermission->selected;
                $rootPermission->selectAll = (bool) $rootPermission->selectAll;
                $rootPermission->view = (bool) $rootPermission->view;
                $rootPermission->create = (bool) $rootPermission->create;
                $rootPermission->edit = (bool) $rootPermission->edit;
                $rootPermission->delete = (bool) $rootPermission->delete;

                // Do the same for children
                foreach ($rootPermission->children as $child) {
                    $child->selected = (bool) $child->selected;
                    $child->selectAll = (bool) $child->selectAll;
                    $child->view = (bool) $child->view;
                    $child->create = (bool) $child->create;
                    $child->edit = (bool) $child->edit;
                    $child->delete = (bool) $child->delete;
                }
            }
            
            return response()->json($rootPermissions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Store permissions
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
          
          foreach ($request->input() as $item) {
            // Check if parent exists
            $existingParent = DB::table('module_permissions')
            // ->where('label', $item['label'])
            //  ->whereNull('parent_id')
                ->where('menu_key', $item['key'])
                ->where('role', $item['role'] ?? 'admin')
                ->first();

            if ($existingParent) {
                // Update existing parent
                DB::table('module_permissions')
                    ->where('id', $existingParent->id)
                    ->update([
                        'selected' => $item['selected'] ?? false,
                        'select_all' => $item['selectAll'] ?? false,
                        'view_permission' => $item['view'] ?? false,
                        'create_permission' => $item['create'] ?? false,
                        'edit_permission' => $item['edit'] ?? false,
                        'delete_permission' => $item['delete'] ?? false,
                    ]);

                $parentId = $existingParent->id;
            } else {
                // Insert new parent
                $parentId = DB::table('module_permissions')->insertGetId([
                    'label' => $item['label'],
                    'parent_id' => null,
                    'selected' => $item['selected'] ?? false,
                    'select_all' => $item['selectAll'] ?? false,
                    'view_permission' => $item['view'] ?? false,
                    'create_permission' => $item['create'] ?? false,
                    'edit_permission' => $item['edit'] ?? false,
                    'delete_permission' => $item['delete'] ?? false,
                    'menu_key' => $item['key'],
                    'role' => $item['role'] ?? 'admin'
                ]);
            }

            // Insert/update children
            if (!empty($item['children']) && is_array($item['children'])) {
                foreach ($item['children'] as $child) {
                    // Same logic: update if exists, insert if not
                    $existingChild = DB::table('module_permissions')
                        ->where('label', $child['label'])
                        ->where('parent_id', $parentId)
                        ->where('menu_key', $child['key'] ?? $item['key'])
                        ->where('role', $child['role'] ?? $item['role'] ?? 'admin')
                        ->first();

                    if ($existingChild) {
                        DB::table('module_permissions')
                            ->where('id', $existingChild->id)
                            ->update([
                                'selected' => $child['selected'] ?? false,
                                'select_all' => $child['selectAll'] ?? false,
                                'view_permission' => $child['view'] ?? false,
                                'create_permission' => $child['create'] ?? false,
                                'edit_permission' => $child['edit'] ?? false,
                                'delete_permission' => $child['delete'] ?? false,
                            ]);
                    } else {
                        DB::table('module_permissions')->insert([
                            'label' => $child['label'],
                            'parent_id' => $parentId,
                            'selected' => $child['selected'] ?? false,
                            'select_all' => $child['selectAll'] ?? false,
                            'view_permission' => $child['view'] ?? false,
                            'create_permission' => $child['create'] ?? false,
                            'edit_permission' => $child['edit'] ?? false,
                            'delete_permission' => $child['delete'] ?? false,
                            'menu_key' => $child['key'] ?? $item['key'],
                            'role' => $child['role'] ?? $item['role'] ?? 'admin'
                        ]);
                    }
                }
            }
        }
  
            DB::commit();
            return response()->json(['message' => 'Permissions have been successfully stored.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}



// <?php

// namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;
// use App\Models\ModulePermission;
// use Illuminate\Support\Facades\DB;
 
 
// class PermissionController extends Controller
// {
// public function store(Request $request)
// { 
//     DB::beginTransaction();
//     try {
//         $savedPermissions = [];

//         foreach ($request->all() as $moduleData) {
//             foreach ($moduleData['sub_modules'] as $subModuleData) {
//                 $permission = ModulePermission::updateOrCreate(
//                     [
//                         'module_name' => $moduleData['module'],
//                         'sub_module_name' => $subModuleData['sub_module_name'] ?? null,
//                         'role' => $moduleData['role'] ?? 'admin'
//                     ],
//                     [
//                         'selected_module' => $moduleData['selected_module'] ?? false,
//                         'select_all' => $subModuleData['selectAll'] ?? false,
//                         'view_permission' => $subModuleData['view'] ?? false,
//                         'create_permission' => $subModuleData['create'] ?? false,
//                         'edit_permission' => $subModuleData['edit'] ?? false,
//                         'delete_permission' => $subModuleData['delete'] ?? false
//                     ]
//                 );

//                 // Add the updated/created record to the result array
//                 $savedPermissions[] = $permission;
//             }
//         }

//         DB::commit();
//         return response()->json([
//             'message' => 'Permissions updated successfully',
//             'data' => $savedPermissions
//         ], 200);
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }


//     // Get All Permissions in Given Format
// public function index(Request $request)
// {
//     $role = $request->query('role');
//     $userId = $request->query('user_id');

//     $query = ModulePermission::query();

//     // Optional: join users if you want to show user_type
//     if ($userId) {
//         $user = \App\Models\User::find($userId);

//         if (!$user) {
//             return response()->json(['error' => 'User not found'], 404);
//         }

//         // filter by user's role
//         $query->where('role', $user->user_type);
//     } elseif ($role) {
//         $query->where('role', $role);
//     }

//     $modules = $query->select('module_name', 'selected_module', 'role')
//         ->groupBy('module_name', 'selected_module', 'role')
//         ->get()
//         ->map(function ($module) use ($userId) {
//             return [
//                 'role' => $module->role,
//                 'module' => $module->module_name,
//                 'selected_module' => (bool) $module->selected_module,
//                 'sub_modules' => ModulePermission::where('module_name', $module->module_name)
//                     ->where('role', $module->role)
//                     ->get([
//                         'sub_module_name', 'select_all',
//                         'view_permission', 'create_permission',
//                         'edit_permission', 'delete_permission'
//                     ])
//                     ->map(function ($subModule) {
//                         return [
//                             'sub_module_name' => $subModule->sub_module_name ?? '',
//                             'selectAll' => (bool) $subModule->select_all,
//                             'view' => (bool) $subModule->view_permission,
//                             'create' => (bool) $subModule->create_permission,
//                             'edit' => (bool) $subModule->edit_permission,
//                             'delete' => (bool) $subModule->delete_permission
//                         ];
//                     })
//             ];
//         });

//     return response()->json($modules);
// }



//     // Update Specific Permission
//     public function update(Request $request, $id)
//     {
//         $permission = ModulePermission::find($id);

//         if (!$permission) {
//             return response()->json(['error' => 'Permission not found'], 404);
//         }

//         $permission->update([
//             'select_all' => $request->input('selectAll', false),
//             'view_permission' => $request->input('view', false),
//             'create_permission' => $request->input('create', false),
//             'edit_permission' => $request->input('edit', false),
//             'delete_permission' => $request->input('delete', false),
//         ]);

//         return response()->json(['message' => 'Permission updated successfully'], 200);
//     }
// }




// <?php

// namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;
// use App\Models\ModulePermission;
// use Illuminate\Support\Facades\DB;
 
 
// class PermissionController extends Controller
// {
// public function store(Request $request)
// { 
//     DB::beginTransaction();
//     try {
//         $savedPermissions = [];

//         foreach ($request->all() as $moduleData) {
//             foreach ($moduleData['sub_modules'] as $subModuleData) {
//                 $permission = ModulePermission::updateOrCreate(
//                     [
//                         'module_name' => $moduleData['module'],
//                         'sub_module_name' => $subModuleData['sub_module_name'] ?? null,
//                         'role' => $moduleData['role'] ?? 'admin'
//                     ],
//                     [
//                         'selected_module' => $moduleData['selected_module'] ?? false,
//                         'select_all' => $subModuleData['selectAll'] ?? false,
//                         'view_permission' => $subModuleData['view'] ?? false,
//                         'create_permission' => $subModuleData['create'] ?? false,
//                         'edit_permission' => $subModuleData['edit'] ?? false,
//                         'delete_permission' => $subModuleData['delete'] ?? false
//                     ]
//                 );

//                 // Add the updated/created record to the result array
//                 $savedPermissions[] = $permission;
//             }
//         }

//         DB::commit();
//         return response()->json([
//             'message' => 'Permissions updated successfully',
//             'data' => $savedPermissions
//         ], 200);
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }


//     // Get All Permissions in Given Format
// public function index(Request $request)
// {
//     $role = $request->query('role');
//     $userId = $request->query('user_id');

//     $query = ModulePermission::query();

//     // Optional: join users if you want to show user_type
//     if ($userId) {
//         $user = \App\Models\User::find($userId);

//         if (!$user) {
//             return response()->json(['error' => 'User not found'], 404);
//         }

//         // filter by user's role
//         $query->where('role', $user->user_type);
//     } elseif ($role) {
//         $query->where('role', $role);
//     }

//     $modules = $query->select('module_name', 'selected_module', 'role')
//         ->groupBy('module_name', 'selected_module', 'role')
//         ->get()
//         ->map(function ($module) use ($userId) {
//             return [
//                 'role' => $module->role,
//                 'module' => $module->module_name,
//                 'selected_module' => (bool) $module->selected_module,
//                 'sub_modules' => ModulePermission::where('module_name', $module->module_name)
//                     ->where('role', $module->role)
//                     ->get([
//                         'sub_module_name', 'select_all',
//                         'view_permission', 'create_permission',
//                         'edit_permission', 'delete_permission'
//                     ])
//                     ->map(function ($subModule) {
//                         return [
//                             'sub_module_name' => $subModule->sub_module_name ?? '',
//                             'selectAll' => (bool) $subModule->select_all,
//                             'view' => (bool) $subModule->view_permission,
//                             'create' => (bool) $subModule->create_permission,
//                             'edit' => (bool) $subModule->edit_permission,
//                             'delete' => (bool) $subModule->delete_permission
//                         ];
//                     })
//             ];
//         });

//     return response()->json($modules);
// }



//     // Update Specific Permission
//     public function update(Request $request, $id)
//     {
//         $permission = ModulePermission::find($id);

//         if (!$permission) {
//             return response()->json(['error' => 'Permission not found'], 404);
//         }

//         $permission->update([
//             'select_all' => $request->input('selectAll', false),
//             'view_permission' => $request->input('view', false),
//             'create_permission' => $request->input('create', false),
//             'edit_permission' => $request->input('edit', false),
//             'delete_permission' => $request->input('delete', false),
//         ]);

//         return response()->json(['message' => 'Permission updated successfully'], 200);
//     }
// }

