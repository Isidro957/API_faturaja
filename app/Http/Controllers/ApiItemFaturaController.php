<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemFatura;
use App\Models\Fatura;

class ApiItemFaturaController extends Controller
{
    /**
     * Listar itens de uma fatura
     */
    public function index($faturaId)
    {
        $tenantId = app('tenant')->id;

        $fatura = Fatura::where('tenant_id', $tenantId)
            ->findOrFail($faturaId);

        return response()->json(
            $fatura->itens()->get()
        );
    }

    /**
     * Criar item da fatura
     */
    public function store(Request $request, $faturaId)
    {
        $tenantId = app('tenant')->id;

        $fatura = Fatura::where('tenant_id', $tenantId)
            ->findOrFail($faturaId);

        $request->validate([
            'produto_id'               => 'nullable|uuid',
            'descricao'                => 'required_without:produto_id|string|max:255',
            'quantidade'               => 'required|integer|min:1',
            'valor_unitario'           => 'required|numeric|min:0',
            'valor_desconto_unitario'  => 'nullable|numeric|min:0',
        ]);

        $item = $fatura->itens()->create([
            'produto_id'              => $request->produto_id,
            'descricao'               => $request->descricao,
            'quantidade'              => $request->quantidade,
            'valor_unitario'          => $request->valor_unitario,
            'valor_desconto_unitario' => $request->valor_desconto_unitario ?? 0,
        ]);

        return response()->json($item, 201);
    }

    /**
     * Mostrar item específico
     */
    public function show($faturaId, $itemId)
    {
        $tenantId = app('tenant')->id;

        $item = ItemFatura::where('id', $itemId)
            ->whereHas('fatura', function ($q) use ($tenantId, $faturaId) {
                $q->where('tenant_id', $tenantId)
                  ->where('id', $faturaId);
            })
            ->firstOrFail();

        return response()->json($item);
    }

    /**
     * Atualizar item
     */
    public function update(Request $request, $faturaId, $itemId)
    {
        $tenantId = app('tenant')->id;

        $item = ItemFatura::where('id', $itemId)
            ->whereHas('fatura', function ($q) use ($tenantId, $faturaId) {
                $q->where('tenant_id', $tenantId)
                  ->where('id', $faturaId);
            })
            ->firstOrFail();

        $request->validate([
            'produto_id'               => 'nullable|uuid',
            'descricao'                => 'required_without:produto_id|string|max:255',
            'quantidade'               => 'required|integer|min:1',
            'valor_unitario'           => 'required|numeric|min:0',
            'valor_desconto_unitario'  => 'nullable|numeric|min:0',
        ]);

        $item->update([
            'produto_id'              => $request->produto_id,
            'descricao'               => $request->descricao,
            'quantidade'              => $request->quantidade,
            'valor_unitario'          => $request->valor_unitario,
            'valor_desconto_unitario' => $request->valor_desconto_unitario ?? 0,
        ]);

        return response()->json($item);
    }

    /**
     * Remover item da fatura
     */
    public function destroy($faturaId, $itemId)
    {
        $tenantId = app('tenant')->id;

        $item = ItemFatura::where('id', $itemId)
            ->whereHas('fatura', function ($q) use ($tenantId, $faturaId) {
                $q->where('tenant_id', $tenantId)
                  ->where('id', $faturaId);
            })
            ->firstOrFail();

        $item->delete();

        return response()->json([
            'message' => 'Item removido da fatura com sucesso'
        ]);
    }
}
