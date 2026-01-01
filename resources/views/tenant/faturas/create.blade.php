@extends('tenant.layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Criar Fatura</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenant.faturas.store', ['tenant' => app('tenant')->subdomain]) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <option value="">Selecione o cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nif_cliente" class="form-label">NIF do Cliente</label>
            <input type="text" name="nif_cliente" id="nif_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="numero" class="form-label">Número da Fatura</label>
            <input type="text" name="numero" id="numero" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="data_emissao" class="form-label">Data de Emissão</label>
            <input type="date" name="data_emissao" id="data_emissao" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
            <input type="date" name="data_vencimento" id="data_vencimento" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="valor_total" class="form-label">Valor Total</label>
            <input type="number" step="0.01" name="valor_total" id="valor_total" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="pendente">Pendente</option>
                <option value="pago">Pago</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="proforma">Proforma</option>
                <option value="fatura">Fatura</option>
                <option value="recibo">Recibo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Criar Fatura</button>
        <a href="{{ route('tenant.faturas.index', ['tenant'=>app('tenant')->subdomain]) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
