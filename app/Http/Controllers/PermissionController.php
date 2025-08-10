<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN']);
    }

    /**
     * Display permission management page
     */
    public function index()
    {
        $roles = Role::with('permissions')->aktif()->get();
        $groupedPermissions = Permission::getGroupedPermissions();
        
        return view('admin.permissions.index', compact('roles', 'groupedPermissions'));
    }

    /**
     * Show role permissions
     */
    public function show($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $groupedPermissions = Permission::getGroupedPermissions();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'role' => $role,
                'permissions' => $groupedPermissions
            ]);
        }
        
        return view('admin.permissions.show', compact('role', 'groupedPermissions'));
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request, $roleId)
    {
        try {
            $request->validate([
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $role = Role::findOrFail($roleId);
            $permissions = $request->permissions ?? [];
            
            // Sync permissions (this will remove old and add new permissions)
            $role->permissions()->sync($permissions);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Hak akses untuk role '{$role->nama_peran}' berhasil diperbarui."
                ]);
            }
            
            return redirect()->route('permissions.index')
                ->with('success', "Hak akses untuk role '{$role->nama_peran}' berhasil diperbarui.");
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reset role permissions (remove all)
     */
    public function resetRolePermissions($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            $role->permissions()->detach();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Semua hak akses untuk role '{$role->nama_peran}' berhasil direset."
                ]);
            }
            
            return redirect()->route('permissions.index')
                ->with('success', "Semua hak akses untuk role '{$role->nama_peran}' berhasil direset.");
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Copy permissions from one role to another
     */
    public function copyPermissions(Request $request)
    {
        try {
            $request->validate([
                'from_role_id' => 'required|exists:peran,id',
                'to_role_id' => 'required|exists:peran,id|different:from_role_id'
            ]);

            $fromRole = Role::with('permissions')->findOrFail($request->from_role_id);
            $toRole = Role::findOrFail($request->to_role_id);
            
            // Get permission IDs from source role
            $permissionIds = $fromRole->permissions->pluck('id')->toArray();
            
            // Sync permissions to target role
            $toRole->permissions()->sync($permissionIds);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Hak akses berhasil disalin dari '{$fromRole->nama_peran}' ke '{$toRole->nama_peran}'."
                ]);
            }
            
            return redirect()->route('permissions.index')
                ->with('success', "Hak akses berhasil disalin dari '{$fromRole->nama_peran}' ke '{$toRole->nama_peran}'.");
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get role permissions for AJAX
     */
    public function getRolePermissions($roleId)
    {
        try {
            $role = Role::with('permissions')->findOrFail($roleId);
            $permissionIds = $role->permissions->pluck('id')->toArray();
            
            return response()->json([
                'success' => true,
                'role' => $role,
                'permission_ids' => $permissionIds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk assign permissions to multiple roles
     */
    public function bulkAssignPermissions(Request $request)
    {
        try {
            $request->validate([
                'role_ids' => 'required|array',
                'role_ids.*' => 'exists:peran,id',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
                'action' => 'required|in:add,replace,remove'
            ]);

            $roleIds = $request->role_ids;
            $permissions = $request->permissions ?? [];
            $action = $request->action;
            $updatedRoles = [];

            foreach ($roleIds as $roleId) {
                $role = Role::findOrFail($roleId);
                
                switch ($action) {
                    case 'add':
                        // Add permissions without removing existing ones
                        $existingPermissions = $role->permissions->pluck('id')->toArray();
                        $newPermissions = array_unique(array_merge($existingPermissions, $permissions));
                        $role->permissions()->sync($newPermissions);
                        break;
                        
                    case 'replace':
                        // Replace all permissions
                        $role->permissions()->sync($permissions);
                        break;
                        
                    case 'remove':
                        // Remove specified permissions
                        $role->permissions()->detach($permissions);
                        break;
                }
                
                $updatedRoles[] = $role->nama_peran;
            }
            
            $actionText = [
                'add' => 'ditambahkan ke',
                'replace' => 'diganti untuk',
                'remove' => 'dihapus dari'
            ];
            
            $message = "Hak akses berhasil {$actionText[$action]} " . count($updatedRoles) . " role: " . implode(', ', $updatedRoles);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->route('permissions.index')->with('success', $message);
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
