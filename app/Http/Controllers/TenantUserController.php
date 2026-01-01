<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\TenantUser;
use Illuminate\Auth\Events\Registered;

class TenantUserController extends Controller
{
    /**
     * Listar usuários do tenant atual
     */
    public function index()
    {
        $tenant = app('tenant');

        $users = TenantUser::where('tenant_id', $tenant->id)->get();

        return view('tenant.users.index', compact('users'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('tenant.users.create');
    }

    /**
     * Salvar novo usuário
     */
   public function store(Request $request)
{
    $tenant = app('tenant');

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique(TenantUser::class, 'email')
                ->where(fn ($q) => $q->where('tenant_id', $tenant->id)),
        ],
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:admin,cliente',
    ]);

    $user = TenantUser::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'tenant_id' => $tenant->id,
        'email_verified_at' => null,
    ]);

    // Enviar notificação de verificação
    $user->sendEmailVerificationNotification();
    event(new Registered($user));

    return redirect()
        ->route('tenant.users.index')
        ->with('success', 'Usuário criado com sucesso! Um link de verificação foi enviado ao email.');
}

    /**
     * Formulário de edição
     */
    public function edit(string $id)
    {
        $tenant = app('tenant');

        $user = TenantUser::where('id', $id)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        return view('tenant.users.edit', compact('user'));
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, string $id)
    {
        $tenant = app('tenant');

        $user = TenantUser::where('id', $id)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique(TenantUser::class, 'email')
                    ->where(fn ($q) => $q->where('tenant_id', $tenant->id))
                    ->ignore($user->id),
            ],
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:admin,cliente',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()
            ->route('tenant.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Excluir usuário
     */
    public function destroy(string $id)
    {
        $tenant = app('tenant');

        $user = TenantUser::where('id', $id)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        $user->delete();

        return redirect()
            ->route('tenant.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}
