<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();  // Identificador da tarefa
            $table->string('nome')->unique();  // Nome da tarefa
            $table->decimal('custo', 10, 2);  // Custo (R$)
            $table->date('data_limite');  // Data limite
            $table->integer('ordem')->unique();  // Ordem de apresentação
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
