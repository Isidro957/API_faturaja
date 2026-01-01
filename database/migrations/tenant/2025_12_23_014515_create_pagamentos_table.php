<?php
namespace Database\Migrations\Tenant;   

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('fatura_id');
            $table->foreign('fatura_id')
                  ->references('id')
                  ->on('faturas')
                  ->onDelete('cascade');

            $table->date('data_pagamento');
            $table->decimal('valor_pago', 10, 2);
            $table->decimal('valor_troco', 10, 2)->default(0);
            $table->decimal('valor_total_desconto', 10, 2)->default(0);
            $table->enum('metodo_pagamento', ['boleto', 'cartão', 'pix']);
            $table->enum('status', ['pendente', 'pago', 'cancelado'])->default('pendente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
