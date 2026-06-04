<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Opd;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        $query = User::with('opd', 'roles');

        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('opd_id', auth()->user()->opd_id);
        }

        $users = $query->orderBy('name')->paginate(20);

        return view('master.user.index', compact('users'));
    }

    public function create()
    {
        $opds = auth()->user()->hasRole('superadmin') ? Opd::where('is_aktif', true)->orderBy('nama')->get() : collect();
        $roles = $this->allowedRoles();

        return view('master.user.create', compact('opds', 'roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $role = $data['role'];
        unset($data['role']);

        if (! auth()->user()->hasRole('superadmin')) {
            $data['opd_id'] = auth()->user()->opd_id;
        }

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $user->assignRole($role);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $opds = auth()->user()->hasRole('superadmin') ? Opd::where('is_aktif', true)->orderBy('nama')->get() : collect();
        $roles = $this->allowedRoles();

        return view('master.user.edit', compact('user', 'opds', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $role = $data['role'];
        unset($data['role']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        if (! auth()->user()->hasRole('superadmin')) {
            unset($data['opd_id']);
        }

        $user->update($data);
        $user->syncRoles([$role]);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->update(['is_aktif' => false]);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dinonaktifkan.');
    }

    private function allowedRoles(): array
    {
        return auth()->user()->hasRole('superadmin')
            ? ['superadmin', 'admin_tu', 'pimpinan', 'staf']
            : ['admin_tu', 'pimpinan', 'staf'];
    }
}
