<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('produtos', function (Blueprint $table) {
    $table->uuid('id')->primary(); 
    $table->uuid('tenant_id'); 
    $table->string('nome', 100);
    $table->text('descricao')->nullable();
    $table->decimal('preco', 10, 2);
    $table->integer('estoque')->default(0);
    $table->enum('tipo', ['produto', 'servico']);
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
