<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionGroup;

class UserController extends Controller
{
    public function index()
    {
        $permissionGroups = PermissionGroup::with('permissions:id,name,permission_group_id')
            ->whereNotNull('prefix')
            ->get();
        return view('konfigurasi.user.index', compact('permissionGroups'));
    }

    public function data(Request $request)
    {
        $query = User::with('roles:id,name');

        if ($request->has('role') && !empty($request->role) && $request->role != 'all') {
            $query->whereHas('roles', function($q) use ($request) {
                if ($request->role == 'Admin') {
                    $q->whereIn('name', ['Admin', 'Super Admin']);
                } else {
                    $q->where('name', $request->role);
                }
            });
        }

        // Bypassing the Gate system entirely for maximum performance
        $me = Auth::user();
        if (!$me->relationLoaded('roles')) {
            $me->load('roles');
        }
        
        $myRoles = $me->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('Super Admin', $myRoles);
        
        // Get permissions directly from DB to avoid any recursion or Gate overhead
        $myPermissions = $this->getPermissionNames($me);

        $canShow = $isSuperAdmin || in_array('user.show', $myPermissions);
        $canEdit = $isSuperAdmin || in_array('user.edit', $myPermissions);
        $canDelete = $isSuperAdmin || in_array('user.delete', $myPermissions);

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('role_names', function ($r) {
                return $r->roles->pluck('name')->map(function($role) {
                    $class = 'badge-secondary';
                    if($role == 'Super Admin' || $role == 'Admin') $class = 'badge-danger';
                    if($role == 'Guru') $class = 'badge-success';
                    if($role == 'Siswa') $class = 'badge-info';
                    if($role == 'ppdb') $class = 'badge-warning';
                    return '<span class="badge '.$class.' px-2 py-1">'.$role.'</span>';
                })->implode(' ') ?: '<span class="badge badge-secondary">No Role</span>';
            })
            ->addColumn('action', function ($query) use ($canShow, $canEdit, $canDelete) {
                $aksi = '';

                if ($canShow) {
                    $aksi .= '
                        <button onclick="detailForm(`' . route('users.detail', $query->id) . '`)" class="btn btn-sm btn-soft-info" title="Detail"><i class="fas fa-eye"></i></button>
                    ';
                }
                if ($canEdit) {
                    $aksi .= '
                        <button onclick="editForm(`' . route('users.edit', $query->id) . '`)" class="btn btn-sm btn-soft-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                    ';
                }
                if ($canDelete) {
                    $aksi .= '
                        <button onclick="resetPassword(`' . route('users.reset_password', $query->id) . '`, `' . $query->name . '`)" class="btn btn-sm btn-soft-warning" title="Reset Password"><i class="fas fa-key"></i></button>
                    ';

                    // Use contains on collection instead of hasRole() to avoid N+1 queries
                    $isAdmin = $query->roles->contains(fn($r) => in_array($r->name, ['Admin', 'Super Admin']));
                    if (!$isAdmin) {
                        $aksi .= '
                            <button onclick="deleteData(`' . route('users.destroy', $query->id) . '`, `' . $query->name . '`)" class="btn btn-sm btn-soft-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                        ';
                    }
                }

                return $aksi;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Maaf inputan yang anda masukan salah, silahkan periksa kembali dan coba lagi'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $roles = Role::find($request->roles);

            $user->assignRole($roles);

            if ($request->has('permission_ids')) {
                $user->syncPermissions(Permission::whereIn('id', $request->permission_ids)->pluck('name')->toArray());
            }

            DB::commit();

            return response()->json([
                'message' => 'User berhasil disimpan',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function detail(Request $request, User $users)
    {
        $users->load(['roles']);
        
        // High-performance permission ID retrieval without hydrating models
        $users->effective_permissions = $this->getPermissionIds($users);
        
        return response()->json([
            'data' => $users
        ]);
    }

    public function edit(Request $request, User $users)
    {
        $users->load(['roles']);
        
        // High-performance permission ID retrieval without hydrating models
        $users->effective_permissions = $this->getPermissionIds($users);

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Get all permission names for a user efficiently.
     */
    private function getPermissionNames(User $user)
    {
        $roleIds = $user->roles->pluck('id')->toArray();
        
        $rolePermissions = DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->whereIn('role_id', $roleIds)
            ->pluck('permissions.name');
            
        $directPermissions = DB::table('model_has_permissions')
            ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
            ->where('model_id', $user->id)
            ->where('model_type', get_class($user))
            ->pluck('permissions.name');
            
        return $rolePermissions->merge($directPermissions)->unique()->toArray();
    }

    /**
     * Get all permission IDs for a user efficiently.
     */
    private function getPermissionIds(User $user)
    {
        $roleIds = $user->roles->pluck('id')->toArray();
        
        $rolePermissions = DB::table('role_has_permissions')
            ->whereIn('role_id', $roleIds)
            ->pluck('permission_id');
            
        $directPermissions = DB::table('model_has_permissions')
            ->where('model_id', $user->id)
            ->where('model_type', get_class($user))
            ->pluck('permission_id');
            
        return $rolePermissions->merge($directPermissions)->unique()->values()->toArray();
    }

    public function show(Request $request)
    {
        return view('profile.show', [
            'request' => $request,
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request, User $users)
    {
        // Pastikan roles adalah sebuah array
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $users->id,
            'roles' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Maaf inputan yang anda masukan salah, silahkan periksa kembali dan coba lagi'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ];

            $users->update($data);

            // Lanjutkan dengan proses update jika roles adalah array
            $roles = Role::find($request->roles);

            $users->syncRoles($roles);

            if ($request->has('permission_ids')) {
                $users->syncPermissions(Permission::whereIn('id', $request->permission_ids)->pluck('name')->toArray());
            } else {
                $users->syncPermissions([]);
            }

            DB::commit();

            return response()->json([
                'message' => 'User berhasil disimpan',
                'data' => $users
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, User $users)
    {
        // Proteksi: Admin dan Super Admin tidak boleh dihapus
        if ($users->hasRole(['Admin', 'Super Admin'])) {
            return response()->json([
                'message' => 'Akun Administrator tidak dapat dihapus demi keamanan sistem.'
            ], 403);
        }

        $users->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ], 200);
    }

    public function roleSearch(Request $request)
    {
        $keyword = request()->get('q');

        $result = Role::where('name', "LIKE", "%$keyword%")
            ->get();

        return $result;
    }

    public function permissionSearch(Request $request)
    {
        $keyword = request()->get('q');

        $result = Permission::where('name', "LIKE", "%$keyword%")
            ->get();

        return $result;
    }

    public function resetPassword(Request $request, User $users)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Inputan tidak valid'
            ], 422);
        }

        $users->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password user ' . $users->name . ' berhasil direset'
        ], 200);
    }
}
