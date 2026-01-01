<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteWebController extends Controller
{
    public function index()
    {
        $tenantId = app('tenant')->id;
        $clients = Cliente::where('tenant_id', $tenantId)->get();
        return view('tenant.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('tenant.clients.create');
    }

    public function store(Request $request)
    {
        $tenantId = app('tenant')->id;
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        Cliente::create(array_merge($request->only('nome','email','telefone'), ['tenant_id'=>$tenantId]));

        return redirect()->route('tenant.clients.index')->with('success','Cliente criado!');
    }

    public function edit($client)
    {
        $tenantId = app('tenant')->id;
        $client = Cliente::where('tenant_id', $tenantId)->where('id', $client)->firstOrFail();
        return view('tenant.clients.edit', compact('client'));
    }

    public function update(Request $request, $client)
    {
        $tenantId = app('tenant')->id;
        $client = Cliente::where('tenant_id', $tenantId)->where('id', $client)->firstOrFail();

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        $client->update($request->only('nome','email','telefone'));
        return redirect()->route('tenant.clients.index')->with('success','Cliente atualizado!');
    }

    public function destroy($client)
    {
        $tenantId = app('tenant')->id;
        $client = Cliente::where('tenant_id', $tenantId)->where('id', $client)->firstOrFail();

        $client->delete();
        return redirect()->route('tenant.clients.index')->with('success','Cliente deletado!');
    }
}
