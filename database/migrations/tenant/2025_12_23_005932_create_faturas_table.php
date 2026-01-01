<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faturas', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('tenant_id');
            $table->uuid('cliente_id');

            $table->string('nif_cliente', 20);
            $table->string('numero', 20);

            $table->date('data_emissao');
            $table->date('data_vencimento');

            $table->decimal('valor_total', 10, 2);

            $table->enum('status', ['pendente', 'pago', 'cancelado'])
                  ->default('pendente');

            $table->enum('tipo', ['proforma', 'fatura', 'recibo'])
                  ->default('fatura');

            
            $table->unique(['tenant_id', 'nif_cliente']);
            $table->unique(['tenant_id', 'numero']);

            
            $table->index('tenant_id');
            $table->index('cliente_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};
