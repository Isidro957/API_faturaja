<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagamento;
use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;

class ApiPagamentoController extends Controller
{
    // Listar pagamentos de uma fatura
    public function index($fatura_id)
    {
        $tenantId = Auth::user()->tenant_id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($fatura_id);

        $pagamentos = $fatura->pagamentos()->get();

        return response()->json($pagamentos);
    }

    // Criar pagamento
    public function store(Request $request, $fatura_id)
    {
        $tenantId = Auth::user()->tenant_id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($fatura_id);

        $request->validate([
            'data_pagamento' => 'required|date',
            'valor_pago' => 'required|numeric|min:0',
            'valor_troco' => 'nullable|numeric|min:0',
            'valor_total_desconto' => 'nullable|numeric|min:0',
            'metodo_pagamento' => 'required|in:boleto,cartão,pix',
        ]);

        $pagamento = $fatura->pagamentos()->create(array_merge(
            $request->all(),
            ['tenant_id' => $tenantId]
        ));

        return response()->json($pagamento, 201);
    }

    // Mostrar pagamento
    public function show($fatura_id, $id)
    {
        $tenantId = Auth::user()->tenant_id;

        $pagamento = Pagamento::where('id', $id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        return response()->json($pagamento);
    }

    // Atualizar pagamento
    public function update(Request $request, $fatura_id, $id)
    {
        $tenantId = Auth::user()->tenant_id;

        $pagamento = Pagamento::where('id', $id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $request->validate([
            'data_pagamento' => 'required|date',
            'valor_pago' => 'required|numeric|min:0',
            'valor_troco' => 'nullable|numeric|min:0',
            'valor_total_desconto' => 'nullable|numeric|min:0',
            'metodo_pagamento' => 'required|in:boleto,cartão,pix',
        ]);

        $pagamento->update($request->all());

        return response()->json($pagamento);
    }

    // Deletar pagamento
    public function destroy($fatura_id, $id)
    {
        $tenantId = Auth::user()->tenant_id;

        $pagamento = Pagamento::where('id', $id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $pagamento->delete();

        return response()->json(['message' => 'Pagamento removido com sucesso']);
    }
}
