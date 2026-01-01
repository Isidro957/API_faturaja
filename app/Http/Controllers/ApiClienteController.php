<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ApiClienteController extends Controller
{
    /**
     * Listar clientes do tenant atual
     */
    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;

        $clientes = Cliente::where('tenant_id', $tenantId)->get();

        return response()->json($clientes);
    }

    /**
     * Criar cliente
     */
    public function store(Request $request)
    {
        $tenantId = app('tenant')->id;

        $request->validate([
            'nome'     => 'required|string|max:100',
            'email'    => 'required|email|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create([
            'tenant_id' => $tenantId,
            'nome'      => $request->nome,
            'email'     => $request->email,
            'telefone'  => $request->telefone,
        ]);

        return response()->json([
            'message' => 'Cliente criado com sucesso',
            'data'    => $cliente
        ], 201);
    }

    /**
     * Mostrar cliente
     */
    public function show($id)
    {
        $tenantId = app('tenant')->id;

        $cliente = Cliente::where('tenant_id', $tenantId)
            ->findOrFail($id);

        return response()->json($cliente);
    }

    /**
     * Atualizar cliente
     */
    public function update(Request $request, $id)
    {
        $tenantId = app('tenant')->id;

        $request->validate([
            'nome'     => 'required|string|max:100',
            'email'    => 'required|email|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::where('tenant_id', $tenantId)
            ->findOrFail($id);

        $cliente->update($request->only('nome', 'email', 'telefone'));

        return response()->json([
            'message' => 'Cliente atualizado com sucesso',
            'data'    => $cliente
        ]);
    }

    /**
     * Remover cliente
     */
    public function destroy($id)
    {
        $tenantId = app('tenant')->id;

        $cliente = Cliente::where('tenant_id', $tenantId)
            ->findOrFail($id);

        $cliente->delete();

        return response()->json([
            'message' => 'Cliente removido com sucesso'
        ]);
    }
}
