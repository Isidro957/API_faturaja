<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\TenantUser;

class ApiAuthController extends Controller
{
    /**
     * LOGIN GLOBAL (descobre tenant pelo email)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        /**
         * 1️⃣ Descobrir tenant pelo email (sem carregar todos)
         */
        $tenant = Tenant::whereExists(function ($query) use ($request) {
            $query->select(DB::raw(1))
                ->from('users')
                ->whereColumn('users.tenant_id', 'tenants.id')
                ->where('users.email', $request->email);
        })->first();

        if (! $tenant) {
            return response()->json([
                'message' => 'Usuário não pertence a nenhuma empresa'
            ], 404);
        }

        /**
         * 2️⃣ Fixar conexão do tenant
         */
        config([
            'database.connections.tenant.database' => $tenant->database_name,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        /**
         * 3️⃣ Buscar usuário no tenant correto
         */
        $user = TenantUser::on('tenant')
            ->where('email', $request->email)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        /**
         * 4️⃣ Criar token COM tenant_id
         */
        $token = $user->createToken('api-token', [
            'tenant_id' => $tenant->id,
        ])->plainTextToken;

        return response()->json([
            'token'    => $token,
            'tenant'   => $tenant->subdomain,
            'user'     => $user,
            'api_url'  => "http://{$tenant->subdomain}.faturaja.sdoca/api",
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }
}
