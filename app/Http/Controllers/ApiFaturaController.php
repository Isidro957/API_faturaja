<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiFaturaController extends Controller
{
    /**
     * Listar faturas do tenant atual
     */
    public function index()
    {
        $tenantId = app('tenant')->id;

        return response()->json(
            Fatura::where('tenant_id', $tenantId)->get()
        );
    }

    /**
     * Criar nova fatura
     */
    public function store(Request $request)
    {
        $tenantId = app('tenant')->id;

        $request->validate([
            'cliente_id'      => 'required|uuid',
            'nif_cliente'     => 'required|string|max:20',
            'numero'          => 'required|string|max:20',
            'data_emissao'    => 'required|date',
            'data_vencimento' => 'required|date',
            'valor_total'     => 'required|numeric|min:0',
            'status'          => 'required|in:pendente,pago,cancelado',
            'tipo'            => 'required|in:proforma,fatura,recibo',
        ]);

        // Cliente pertence ao tenant?
        Cliente::where('id', $request->cliente_id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        // UNIQUE manual por tenant
        if (Fatura::where('tenant_id', $tenantId)
            ->where('numero', $request->numero)
            ->exists()) {
            return response()->json([
                'message' => 'Número da fatura já existe neste tenant'
            ], 422);
        }

        $fatura = Fatura::create([
            'id'              => Str::uuid(),
            'tenant_id'       => $tenantId,
            'cliente_id'      => $request->cliente_id,
            'nif_cliente'     => $request->nif_cliente,
            'numero'          => $request->numero,
            'data_emissao'    => $request->data_emissao,
            'data_vencimento' => $request->data_vencimento,
            'valor_total'     => $request->valor_total,
            'status'          => $request->status,
            'tipo'            => $request->tipo,
        ]);

        return response()->json($fatura, 201);
    }

    /**
     * Mostrar fatura
     */
    public function show($id)
    {
        return response()->json(
            Fatura::where('tenant_id', app('tenant')->id)
                ->findOrFail($id)
        );
    }

    /**
     * Atualizar fatura
     */
    public function update(Request $request, $id)
    {
        $tenantId = app('tenant')->id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($id);

        $request->validate([
            'cliente_id'      => 'required|uuid',
            'nif_cliente'     => 'required|string|max:20',
            'numero'          => 'required|string|max:20',
            'data_emissao'    => 'required|date',
            'data_vencimento' => 'required|date',
            'valor_total'     => 'required|numeric|min:0',
            'status'          => 'required|in:pendente,pago,cancelado',
            'tipo'            => 'required|in:proforma,fatura,recibo',
        ]);

        $fatura->update($request->only([
            'cliente_id',
            'nif_cliente',
            'numero',
            'data_emissao',
            'data_vencimento',
            'valor_total',
            'status',
            'tipo'
        ]));

        return response()->json($fatura);
    }

    /**
     * Deletar fatura
     */
    public function destroy($id)
    {
        Fatura::where('tenant_id', app('tenant')->id)
            ->findOrFail($id)
            ->delete();

        return response()->json([
            'message' => 'Fatura deletada com sucesso'
        ]);
    }
}
