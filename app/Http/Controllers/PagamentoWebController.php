<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagamento;
use App\Models\Fatura;

class PagamentoWebController extends Controller
{
    /**
     * Listar pagamentos de uma fatura
     */
    public function index($fatura_id)
    {
        $tenantId = app('tenant')->id;

        // Busca a fatura apenas do tenant atual
        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($fatura_id);

        // Busca todos os pagamentos da fatura
        $pagamentos = $fatura->pagamentos()->get();

        return view('tenant.pagamentos.index', compact('pagamentos', 'fatura'));
    }

    /**
     * Formulário para criar pagamento
     */
public function create($fatura_id = null)
{
    // Se $fatura_id for null, no formulário você pode permitir escolher a fatura
    $faturas = $fatura_id ? [] : Fatura::where('tenant_id', app('tenant')->id)->get();
    return view('tenant.pagamentos.create', compact('fatura_id', 'faturas'));
}

    /**
     * Salvar novo pagamento
     */
    public function store(Request $request, $fatura_id)
    {
        $tenantId = app('tenant')->id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($fatura_id);

        $request->validate([
            'data_pagamento' => 'required|date',
            'valor_pago' => 'required|numeric|min:0',
            'valor_troco' => 'nullable|numeric|min:0',
            'valor_total_desconto' => 'nullable|numeric|min:0',
            'metodo_pagamento' => 'required|in:boleto,cartão,pix',
        ]);

        // Adiciona tenant_id antes de criar
        $fatura->pagamentos()->create(array_merge(
            $request->all(),
            ['tenant_id' => $tenantId]
        ));

        return redirect()->route('tenant.faturas.pagamentos.index', $fatura_id)
                         ->with('success', 'Pagamento registrado com sucesso!');
    }

    /**
     * Formulário para editar pagamento
     */
    public function edit($fatura_id, $id)
    {
        $tenantId = app('tenant')->id;

        $pagamento = Pagamento::where('id', $id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        return view('tenant.pagamentos.edit', compact('pagamento', 'fatura_id'));
    }

    /**
     * Atualizar pagamento
     */
    public function update(Request $request, $fatura_id, $id)
    {
        $tenantId = app('tenant')->id;

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

        return redirect()->route('tenant.faturas.pagamentos.index', $fatura_id)
                         ->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remover pagamento
     */
    public function destroy($fatura_id, $id)
    {
        $tenantId = app('tenant')->id;

        $pagamento = Pagamento::where('id', $id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $pagamento->delete();

        return redirect()->route('tenant.faturas.pagamentos.index', $fatura_id)
                         ->with('success', 'Pagamento removido com sucesso!');
    }


    public function all()
{
    $tenantId = app('tenant')->id;
    $pagamentos = Pagamento::where('tenant_id', $tenantId)->get();
    return view('tenant.pagamentos.index', compact('pagamentos'));
}

}
