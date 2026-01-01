<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemFatura;
use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;

class ApiItemFaturaController extends Controller
{
    // Listar itens de uma fatura
    public function index($fatura_id)
    {
        $tenantId = Auth::user()->tenant_id;
        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($fatura_id);

        $itens = $fatura->itens()->get();
        return response()->json($itens);
    }

    // Criar item
    public function store(Request $request, $fatura_id)
    {
        $tenantId = Auth::user()->tenant_id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($fatura_id);

        $request->validate([
            'produto_id' => 'nullable|exists:produtos,id',
            'descricao' => 'nullable|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'valor_unitario' => 'required|numeric|min:0',
            'valor_desconto_unitario' => 'required|numeric|min:0',
        ]);

        $item = ItemFatura::create(array_merge($request->all(), ['fatura_id' => $fatura->id]));

        return response()->json($item, 201);
    }

    // Mostrar item
    public function show($fatura_id, $id)
    {
        $tenantId = Auth::user()->tenant_id;
        $item = ItemFatura::whereHas('fatura', fn($q) => $q->where('tenant_id', $tenantId))
                          ->findOrFail($id);
        return response()->json($item);
    }

    // Atualizar item
    public function update(Request $request, $fatura_id, $id)
    {
        $tenantId = Auth::user()->tenant_id;
        $item = ItemFatura::whereHas('fatura', fn($q) => $q->where('tenant_id', $tenantId))
                          ->findOrFail($id);

        $request->validate([
            'produto_id' => 'nullable|exists:produtos,id',
            'descricao' => 'nullable|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'valor_unitario' => 'required|numeric|min:0',
            'valor_desconto_unitario' => 'required|numeric|min:0',
        ]);

        $item->update($request->all());

        return response()->json($item);
    }

    // Deletar item
    public function destroy($fatura_id, $id)
    {
        $tenantId = Auth::user()->tenant_id;
        $item = ItemFatura::whereHas('fatura', fn($q) => $q->where('tenant_id', $tenantId))
                          ->findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item removido da fatura!']);
    }
}
