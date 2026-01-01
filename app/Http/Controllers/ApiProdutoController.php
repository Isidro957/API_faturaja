<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;

class ApiProdutoController extends Controller
{
    public function index()
    {
        return Produto::doTenant()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
            'tipo' => 'required|in:produto,servico',
        ]);

        $produto = Produto::create([
            'tenant_id' => app('tenant')->id,
            ...$request->only('nome','descricao','preco','estoque','tipo')
        ]);

        return response()->json($produto, 201);
    }

    public function show($id)
    {
        return Produto::doTenant()->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::doTenant()->findOrFail($id);

        $produto->update(
            $request->only('nome','descricao','preco','estoque','tipo')
        );

        return response()->json($produto);
    }

    public function destroy($id)
    {
        Produto::doTenant()->findOrFail($id)->delete();
        return response()->json(['message' => 'Removido com sucesso']);
    }
}
