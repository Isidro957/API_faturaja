<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fatura;
use App\Models\Cliente;
use Illuminate\Support\Str;

class FaturaWebController extends Controller
{
    /**
     * Listar todas as faturas do tenant
     */
    public function index()
    {
        $tenantId = app('tenant')->id;

        $faturas = Fatura::where('tenant_id', $tenantId)->get();

        return view('tenant.faturas.index', compact('faturas'));
    }

    /**
     * Formulário para criar nova fatura
     */
    public function create()
    {
        $tenantId = app('tenant')->id;

        $clientes = Cliente::where('tenant_id', $tenantId)->get();

        return view('tenant.faturas.create', compact('clientes'));
    }

    /**
     * Salvar nova fatura
     */
    public function store(Request $request)
    {
        $tenantId = app('tenant')->id;

        // ✅ Validação simples (SEM unique)
        $request->validate([
            'cliente_id' => 'required|uuid',
            'nif_cliente' => 'required|string|max:20',
            'numero' => 'required|string|max:20',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'valor_total' => 'required|numeric|min:0',
            'status' => 'required|in:pendente,pago,cancelado',
            'tipo' => 'required|in:proforma,fatura,recibo',
        ]);

        // ✅ Garante que o cliente pertence ao tenant
        Cliente::where('id', $request->cliente_id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        // ✅ UNIQUE manual (tenant-safe)
        if (Fatura::where('tenant_id', $tenantId)
            ->where('nif_cliente', $request->nif_cliente)
            ->exists()) {
            return back()->withErrors([
                'nif_cliente' => 'NIF já existe neste tenant'
            ])->withInput();
        }

        if (Fatura::where('tenant_id', $tenantId)
            ->where('numero', $request->numero)
            ->exists()) {
            return back()->withErrors([
                'numero' => 'Número da fatura já existe neste tenant'
            ])->withInput();
        }

        // ✅ Criar fatura
        Fatura::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenantId,
            'cliente_id' => $request->cliente_id,
            'nif_cliente' => $request->nif_cliente,
            'numero' => $request->numero,
            'data_emissao' => $request->data_emissao,
            'data_vencimento' => $request->data_vencimento,
            'valor_total' => $request->valor_total,
            'status' => $request->status,
            'tipo' => $request->tipo,
        ]);

        return redirect()
            ->route('tenant.faturas.index')
            ->with('success', 'Fatura criada com sucesso!');
    }

    /**
     * Formulário para editar fatura
     */
    public function edit($id)
    {
        $tenantId = app('tenant')->id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($id);
        $clientes = Cliente::where('tenant_id', $tenantId)->get();

        return view('tenant.faturas.edit', compact('fatura', 'clientes'));
    }

    /**
     * Atualizar fatura existente
     */
    public function update(Request $request, $id)
    {
        $tenantId = app('tenant')->id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($id);

        $request->validate([
            'cliente_id' => 'required|uuid',
            'nif_cliente' => 'required|string|max:20',
            'numero' => 'required|string|max:20',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'valor_total' => 'required|numeric|min:0',
            'status' => 'required|in:pendente,pago,cancelado',
            'tipo' => 'required|in:proforma,fatura,recibo',
        ]);

        // ✅ UNIQUE manual (ignore a própria fatura)
        if (
            Fatura::where('tenant_id', $tenantId)
                ->where('nif_cliente', $request->nif_cliente)
                ->where('id', '!=', $fatura->id)
                ->exists()
        ) {
            return back()->withErrors([
                'nif_cliente' => 'NIF já existe neste tenant'
            ])->withInput();
        }

        if (
            Fatura::where('tenant_id', $tenantId)
                ->where('numero', $request->numero)
                ->where('id', '!=', $fatura->id)
                ->exists()
        ) {
            return back()->withErrors([
                'numero' => 'Número da fatura já existe neste tenant'
            ])->withInput();
        }

        // ✅ Confirma cliente do tenant
        Cliente::where('id', $request->cliente_id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $fatura->update([
            'cliente_id' => $request->cliente_id,
            'nif_cliente' => $request->nif_cliente,
            'numero' => $request->numero,
            'data_emissao' => $request->data_emissao,
            'data_vencimento' => $request->data_vencimento,
            'valor_total' => $request->valor_total,
            'status' => $request->status,
            'tipo' => $request->tipo,
        ]);

        return redirect()
            ->route('tenant.faturas.index')
            ->with('success', 'Fatura atualizada com sucesso!');
    }

    /**
     * Deletar fatura
     */
    public function destroy($id)
    {
        $tenantId = app('tenant')->id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($id);
        $fatura->delete();

        return redirect()
            ->route('tenant.faturas.index')
            ->with('success', 'Fatura eliminada com sucesso!');
    }
}
