<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagamento;
use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;

class ApiPagamentoController extends Controller
{
    /**
     * Buscar o tenant atual
     */
    private function tenantId()
    {
        return Auth::user()->tenant_id;
    }

    /**
     * Buscar pagamento garantindo que pertence ao tenant
     */
    private function findPagamento($id)
    {
        return Pagamento::where('id', $id)
                        ->where('tenant_id', $this->tenantId())
                        ->firstOrFail();
    }

    /**
     * Listar pagamentos de uma fatura
     */
    public function index($fatura_id)
    {
        $fatura = Fatura::where('tenant_id', $this->tenantId())
                        ->findOrFail($fatura_id);

        $pagamentos = $fatura->pagamentos()->get();

        return response()->json($pagamentos);
    }

    /**
     * Listar todos os pagamentos do tenant
     */
    public function all()
    {
        $pagamentos = Pagamento::where('tenant_id', $this->tenantId())
                               ->with('fatura') // opcional: incluir dados da fatura
                               ->get();

        return response()->json($pagamentos);
    }

    /**
     * Criar pagamento
     */
    public function store(Request $request, $fatura_id)
    {
        $fatura = Fatura::where('tenant_id', $this->tenantId())
                        ->findOrFail($fatura_id);

        $validated = $request->validate([
            'data_pagamento' => 'required|date',
            'valor_pago' => 'required|numeric|min:0',
            'valor_troco' => 'nullable|numeric|min:0',
            'valor_total_desconto' => 'nullable|numeric|min:0',
            'metodo_pagamento' => 'required|in:boleto,cartão,pix',
        ]);

        $pagamento = $fatura->pagamentos()->create(array_merge(
            $validated,
            ['tenant_id' => $this->tenantId()]
        ));

        return response()->json([
            'message' => 'Pagamento registrado com sucesso!',
            'pagamento' => $pagamento
        ], 201);
    }

    /**
     * Mostrar um pagamento específico
     */
    public function show($fatura_id, $id)
    {
        $pagamento = $this->findPagamento($id);

        return response()->json($pagamento->load('fatura')); // inclui dados da fatura
    }

    /**
     * Atualizar pagamento
     */
    public function update(Request $request, $fatura_id, $id)
    {
        $pagamento = $this->findPagamento($id);

        $validated = $request->validate([
            'data_pagamento' => 'required|date',
            'valor_pago' => 'required|numeric|min:0',
            'valor_troco' => 'nullable|numeric|min:0',
            'valor_total_desconto' => 'nullable|numeric|min:0',
            'metodo_pagamento' => 'required|in:boleto,cartão,pix',
        ]);

        $pagamento->update($validated);

        return response()->json([
            'message' => 'Pagamento atualizado com sucesso!',
            'pagamento' => $pagamento
        ]);
    }

    /**
     * Deletar pagamento
     */
    public function destroy($fatura_id, $id)
    {
        $pagamento = $this->findPagamento($id);

        $pagamento->delete();

        return response()->json(['message' => 'Pagamento removido com sucesso!']);
    }
}
