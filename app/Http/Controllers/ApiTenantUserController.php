<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\TenantUser;
use Illuminate\Auth\Events\Registered;

class ApiTenantUserController extends Controller
{
    /**
     * Listar usuários do tenant
     */
    public function index()
    {
        $tenantId = app('tenant')->id;

        $users = TenantUser::where('tenant_id', $tenantId)->get();

        return response()->json($users);
    }

    /**
     * Criar novo usuário
     */
    public function store(Request $request)
    {
        $tenantId = app('tenant')->id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique(TenantUser::class, 'email')->where(fn($q) => $q->where('tenant_id', $tenantId)),
            ],
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,cliente',
        ]);

        $user = TenantUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'tenant_id' => $tenantId,
        ]);

        // Enviar email de verificação
        $user->sendEmailVerificationNotification();
        event(new Registered($user));

        return response()->json([
            'message' => 'Usuário criado com sucesso! Um link de verificação foi enviado ao email.',
            'data' => $user
        ], 201);
    }

    /**
     * Mostrar usuário específico
     */
    public function show($id)
    {
        $tenantId = app('tenant')->id;

        $user = TenantUser::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($user);
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, $id)
    {
        $tenantId = app('tenant')->id;

        $user = TenantUser::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique(TenantUser::class, 'email')->ignore($user->id)->where(fn($q) => $q->where('tenant_id', $tenantId)),
            ],
            'role' => 'required|in:admin,cliente',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return response()->json([
            'message' => 'Usuário atualizado com sucesso!',
            'data' => $user
        ]);
    }

    /**
     * Deletar usuário
     */
    public function destroy($id)
    {
        $tenantId = app('tenant')->id;

        $user = TenantUser::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        // Evitar deletar o próprio usuário logado
        if(auth()->guard('tenant')->id() === $user->id){
            return response()->json([
                'message' => 'Você não pode deletar sua própria conta!'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuário deletado com sucesso!'
        ]);
    }
}
