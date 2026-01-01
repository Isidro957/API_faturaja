<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID como PK
            $table->uuid('tenant_id'); // ID do tenant (empresa)
            $table->string('nome', 100); // obrigatório
            $table->string('email', 100); // obrigatório
            $table->string('telefone', 20)->nullable(); // opcional
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
