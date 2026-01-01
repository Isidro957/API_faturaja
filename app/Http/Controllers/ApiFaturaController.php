<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fatura;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiFaturaController extends Controller
{
    /**
     * Listar todas as faturas do tenant
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $faturas = Fatura::where('tenant_id', $tenantId)->get();

        return response()->json($faturas);
    }

    /**
     * Criar nova fatura
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        // ✅ Validação SEM unique
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

        // ✅ Cliente pertence ao tenant?
        Cliente::where('id', $request->cliente_id)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        // ✅ UNIQUE manual (tenant-safe)
        if (
            Fatura::where('tenant_id', $tenantId)
                ->where('nif_cliente', $request->nif_cliente)
                ->exists()
        ) {
            return response()->json([
                'message' => 'NIF já existe neste tenant'
            ], 422);
        }

        if (
            Fatura::where('tenant_id', $tenantId)
                ->where('numero', $request->numero)
                ->exists()
        ) {
            return response()->json([
                'message' => 'Número da fatura já existe neste tenant'
            ], 422);
        }

        $fatura = Fatura::create([
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

        return response()->json([
            'message' => 'Fatura criada com sucesso',
            'data' => $fatura
        ], 201);
    }

    /**
     * Mostrar fatura específica
     */
    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($id);

        return response()->json($fatura);
    }

    /**
     * Atualizar fatura existente
     */
    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id;

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

        // ✅ UNIQUE manual (ignora a própria fatura)
        if (
            Fatura::where('tenant_id', $tenantId)
                ->where('nif_cliente', $request->nif_cliente)
                ->where('id', '!=', $fatura->id)
                ->exists()
        ) {
            return response()->json([
                'message' => 'NIF já existe neste tenant'
            ], 422);
        }

        if (
            Fatura::where('tenant_id', $tenantId)
                ->where('numero', $request->numero)
                ->where('id', '!=', $fatura->id)
                ->exists()
        ) {
            return response()->json([
                'message' => 'Número da fatura já existe neste tenant'
            ], 422);
        }

        // ✅ Cliente pertence ao tenant
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

        return response()->json([
            'message' => 'Fatura atualizada com sucesso',
            'data' => $fatura
        ]);
    }

    /**
     * Deletar fatura
     */
    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;

        $fatura = Fatura::where('tenant_id', $tenantId)->findOrFail($id);
        $fatura->delete();

        return response()->json([
            'message' => 'Fatura deletada com sucesso'
        ]);
    }
}
