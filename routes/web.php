<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefaController;

Route::get('/', [TarefaController::class, 'index'])->name('tarefas.index');
Route::post('/tarefas', [TarefaController::class, 'store'])->name('tarefas.store');
Route::get('/tarefas/{tarefa}/edit', [TarefaController::class, 'edit'])->name('tarefas.edit');
Route::put('/tarefas/{tarefa}', [TarefaController::class, 'update'])->name('tarefas.update');
Route::delete('/tarefas/{id}', [TarefaController::class, 'destroy'])->name('tarefas.destroy');
Route::post('/tarefas/{id}/updateOrder', [TarefaController::class, 'updateOrder'])->name('tarefas.updateOrder');
Route::post('/tarefas/updateOrder', [TarefaController::class, 'updateOrderFromDrag'])->name('tarefas.updateOrder');
