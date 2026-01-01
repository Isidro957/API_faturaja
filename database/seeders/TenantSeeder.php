<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $databaseName = 'bai_bd'; // nome do banco do tenant

        // 1️⃣ Cria o database do tenant se não existir
        DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

        // 2️⃣ Cria o tenant no banco landlord
        $tenant = Tenant::create([
            'id' => Str::uuid(),
            'name' => 'BAI',
            'subdomain' => 'bai',
            'database_name' => $databaseName,
        ]);

        $this->command->info("Tenant '{$tenant->name}' criado no landlord com sucesso!");

        // 3️⃣ Configura a conexão tenant dinamicamente
        config(['database.connections.tenant.database' => $databaseName]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        $this->command->info("Conexão tenant configurada para '{$databaseName}'");

        // 4️⃣ Roda as migrations específicas do tenant
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant', // caminho correto relativo à raiz do projeto
            '--force' => true,
        ]);

        $this->command->info("Migrations do tenant rodadas com sucesso!");
        $this->command->info(Artisan::output()); // mostra o log das migrations

        // 5️⃣ Cria usuários iniciais (admin e cliente)
        DB::connection('tenant')->table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Vanio',
                'email' => 'vanioneto709@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'tenant_id' => $tenant->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Paulina Capitao',
                'email' => 'capitaopaulinafernando@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'cliente',
                'tenant_id' => $tenant->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $this->command->info("Usuários admin e cliente inseridos no tenant '{$databaseName}' com sucesso!");
    }
}
