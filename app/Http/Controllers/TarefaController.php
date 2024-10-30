<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function index()
    {
        // Busca todas as tarefas ordenadas pelo campo 'ordem'
        $tarefas = Tarefa::orderBy('ordem')->get();
        return view('tarefas.index', compact('tarefas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tarefas,nome',
            'custo' => 'required|numeric|min:0',
            'data_limite' => 'required|date',
        ], [
            'nome.unique' => 'Já existe uma tarefa com esse nome. Escolha outro nome.',
        ]);

        // Definindo a nova ordem como a última
        $ultimaOrdem = Tarefa::max('ordem') ?? 0;
        Tarefa::create([
            'nome' => $request->nome,
            'custo' => $request->custo,
            'data_limite' => $request->data_limite,
            'ordem' => $ultimaOrdem + 1,
        ]);

        return redirect()->route('tarefas.index')->with('success', 'Tarefa adicionada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'custo' => 'required|numeric|min:0',
            'data_limite' => 'required|date',
        ]);

        // Verifica se o novo nome já existe
        $existingTarefa = Tarefa::where('nome', $request->nome)
            ->where('id', '!=', $id) // Ignora a tarefa atual
            ->first();

        if ($existingTarefa) {
            return redirect()->route('tarefas.index')->with('error', 'Já existe uma tarefa com esse nome.');
        }

        // Atualiza a tarefa
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->update($request->only('nome', 'custo', 'data_limite'));

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete(); // Exclui a tarefa

        // Redireciona com mensagem de sucesso
        return redirect()->route('tarefas.index')->with('success', 'Tarefa excluída com sucesso.');
    }

    public function updateOrderFromDrag(Request $request)
{
    $orderData = $request->json()->all();

    foreach ($orderData as $item) {
        $tarefa = Tarefa::find($item['id']);
        $tarefa->ordem = $item['ordem'];
        $tarefa->save();
    }

    return response()->json(['message' => 'Ordem atualizada com sucesso']);
}

}
