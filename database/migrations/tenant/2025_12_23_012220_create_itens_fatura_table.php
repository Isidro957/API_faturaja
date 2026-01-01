<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itens_fatura', function (Blueprint $table) {
    $table->id();
    $table->uuid('fatura_id'); // UUID da fatura
    $table->foreign('fatura_id')->references('id')->on('faturas')->cascadeOnDelete();

    $table->uuid('produto_id')->nullable(); // UUID do produto
    $table->foreign('produto_id')->references('id')->on('produtos')->nullOnDelete();

    $table->string('descricao')->nullable();
    $table->integer('quantidade')->default(1);
    $table->decimal('valor_unitario', 10, 2);
    $table->decimal('valor_desconto_unitario', 10, 2)->default(0);
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('itens_fatura');
    }
};
