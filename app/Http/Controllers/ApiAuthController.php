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
     * LOGIN POR EMAIL (descobre tenant automaticamente)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 1️⃣ Descobrir tenant percorrendo todos os tenants
        $tenants = Tenant::all();
        $tenant = null;
        $user = null;

        foreach ($tenants as $t) {
            config(['database.connections.tenant.database' => $t->database_name]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            $u = TenantUser::on('tenant')->where('email', $request->email)->first();
            if ($u) {
                $tenant = $t;
                $user = $u;
                break;
            }
        }

        if (!$tenant || !$user) {
            return response()->json([
                'message' => 'Empresa não encontrada para este email'
            ], 404);
        }

        // 2️⃣ Verificar senha
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        // 3️⃣ Criar token no landlord (onde a tabela personal_access_tokens existe)
        $token = $user
            ->setConnection('landlord')
            ->createToken('api-token')
            ->plainTextToken;

        // 4️⃣ Retornar dados para frontend
        return response()->json([
            'success'  => true,
            'token'    => $token,
            'tenant'   => $tenant->subdomain,
            'user'     => $user,
            'redirect' => '/dashboard',
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }
}
