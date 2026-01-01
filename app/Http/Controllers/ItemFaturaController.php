<?php

namespace App\Http\Controllers;

use App\Models\ItemFatura;
use App\Models\Fatura;
use App\Models\Produto;
use Illuminate\Http\Request;

class ItemFaturaController extends Controller
{
    public function index($fatura_id)
    {
        $fatura = Fatura::findOrFail($fatura_id);
        $itens = $fatura->itens;
        return view('tenant.itens_fatura.index', compact('fatura', 'itens'));
    }

    public function create($fatura_id)
    {
        $fatura = Fatura::findOrFail($fatura_id);
        $produtos = Produto::all();
        return view('tenant.itens_fatura.create', compact('fatura', 'produtos'));
    }

    public function store(Request $request, $fatura_id)
    {
        $request->validate([
            'produto_id' => 'nullable|exists:produtos,id',
            'descricao' => 'nullable|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'valor_unitario' => 'required|numeric|min:0',
            'valor_desconto_unitario' => 'required|numeric|min:0',
        ]);

        ItemFatura::create(array_merge($request->all(), ['fatura_id' => $fatura_id]));

        return redirect()->route('tenant.itens_fatura.index', ['fatura' => $fatura_id])
                         ->with('success', 'Item adicionado à fatura!');
    }

    public function edit($fatura_id, $id)
    {
        $item = ItemFatura::findOrFail($id);
        $produtos = Produto::all();
        return view('tenant.itens_fatura.edit', compact('item', 'produtos'));
    }

    public function update(Request $request, $fatura_id, $id)
    {
        $request->validate([
            'produto_id' => 'nullable|exists:produtos,id',
            'descricao' => 'nullable|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'valor_unitario' => 'required|numeric|min:0',
            'valor_desconto_unitario' => 'required|numeric|min:0',
        ]);

        $item = ItemFatura::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('tenant.itens_fatura.index', ['fatura' => $fatura_id])
                         ->with('success', 'Item atualizado!');
    }

    public function destroy($fatura_id, $id)
    {
        $item = ItemFatura::findOrFail($id);
        $item->delete();

        return redirect()->route('tenant.itens_fatura.index', ['fatura' => $fatura_id])
                         ->with('success', 'Item removido da fatura!');
    }
}
