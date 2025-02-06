<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use App\Services\Contracts\RoleServiceInterface;

class RoleController extends Controller
{
    /**
     * @var RoleServiceInterface $roleService
     */
    protected $roleService;

    /**
     * Konstruktor RoleController.
     */
    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter status dari query string
        $status = $request->query('status');

        if ($status === null) {
            // Jika tidak ada query parameter, ambil semua role
            $roles = $this->roleService->getAllRoles();
        } elseif ($status == 1) {
            // Jika status = 1, ambil role dengan status aktif
            $roles = $this->roleService->getActiveRoles();
        } elseif ($status == 0) {
            // Jika status = 0 ambil role dengan status tidak aktif
            $roles = $this->roleService->getInactiveRoles();
        } else {
            return response()->json(['error' => 'Invalid status parameter'], 400);
        }
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        $role = $this->roleService->createRole($request->all());
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->roleService->getRoleById($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found.'], 404);
        }
        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, string $id)
    {
        $role = $this->roleService->updateRole($id, $request->all());
        if (!$role) {
            return response()->json(['message' => 'Role not found.'], 404);
        }
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->roleService->deleteRole($id);

        if (!$deleted) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
