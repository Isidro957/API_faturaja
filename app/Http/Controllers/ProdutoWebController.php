<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoWebController extends Controller
{
    public function index()
    {
        $produtos = Produto::doTenant()->get();
        return view('tenant.produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('tenant.produtos.create');
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

        Produto::create([
            'tenant_id' => app('tenant')->id,
            ...$request->only('nome','descricao','preco','estoque','tipo')
        ]);

        return redirect()
            ->route('tenant.produtos.index')
            ->with('success', 'Produto/Serviço criado com sucesso!');
    }

    public function edit($id)
    {
        $produto = Produto::doTenant()->findOrFail($id);
        return view('tenant.produtos.edit', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::doTenant()->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
            'tipo' => 'required|in:produto,servico',
        ]);

        $produto->update(
            $request->only('nome','descricao','preco','estoque','tipo')
        );

        return redirect()
            ->route('tenant.produtos.index')
            ->with('success', 'Produto/Serviço atualizado!');
    }

    public function destroy($id)
    {
        $produto = Produto::doTenant()->findOrFail($id);
        $produto->delete();

        return redirect()
            ->route('tenant.produtos.index')
            ->with('success', 'Produto/Serviço removido!');
    }
}
