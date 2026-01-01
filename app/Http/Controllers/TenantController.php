<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        // Validação básica
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|unique:tenants,subdomain',
        ]);

        $databaseName = 'tenant_' . Str::slug($request->subdomain);

        // 1️⃣ Criar database físico
        $this->createTenantDatabase($databaseName);

        // 2️⃣ Criar tenant no banco principal
        $tenant = Tenant::create([
            'id' => (string) Str::uuid(), // UUID
            'name' => $request->name,
            'subdomain' => $request->subdomain,
            'database_name' => $databaseName,
        ]);

        // 3️⃣ Configurar conexão do tenant
        config(['database.connections.tenant.database' => $databaseName]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant'); // força o uso da conexão tenant

        // 4️⃣ Forçar PDO a usar a database
        DB::connection('tenant')->getPdo();

        // 5️⃣ Rodar migrations do tenant
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant', // relativo à raiz do projeto
            '--force' => true,
        ]);

        // 6️⃣ (Opcional) Rodar seeders do tenant
        Artisan::call('db:seed', [
            '--class' => 'TenantDatabaseSeeder', // crie este seeder
            '--database' => 'tenant',
            '--force' => true,
        ]);

        return response()->json([
            'tenant' => $tenant,
            'message' => 'Tenant criado com sucesso e database inicializado.'
        ]);
    }

    /**
     * Cria o banco de dados do tenant
     */
    protected function createTenantDatabase(string $databaseName)
    {
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        } catch (\Exception $e) {
            abort(500, "Erro ao criar database: " . $e->getMessage());
        }
    }
}
