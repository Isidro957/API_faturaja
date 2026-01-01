@extends('tenant.layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Editar Fatura #{{ $fatura->numero }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenant.faturas.update', ['tenant' => app('tenant')->subdomain, 'id' => $fatura->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <option value="">Selecione o cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->id == $fatura->cliente_id ? 'selected' : '' }}>
                        {{ $cliente->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nif_cliente" class="form-label">NIF do Cliente</label>
            <input type="text" name="nif_cliente" id="nif_cliente" class="form-control" value="{{ $fatura->nif_cliente }}" required>
        </div>

        <div class="mb-3">
            <label for="numero" class="form-label">Número da Fatura</label>
            <input type="text" name="numero" id="numero" class="form-control" value="{{ $fatura->numero }}" required>
        </div>

        <div class="mb-3">
            <label for="data_emissao" class="form-label">Data de Emissão</label>
            <input type="date" name="data_emissao" id="data_emissao" class="form-control" value="{{ $fatura->data_emissao->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
            <input type="date" name="data_vencimento" id="data_vencimento" class="form-control" value="{{ $fatura->data_vencimento->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="valor_total" class="form-label">Valor Total</label>
            <input type="number" step="0.01" name="valor_total" id="valor_total" class="form-control" value="{{ $fatura->valor_total }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="pendente" {{ $fatura->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="pago" {{ $fatura->status == 'pago' ? 'selected' : '' }}>Pago</option>
                <option value="cancelado" {{ $fatura->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="proforma" {{ $fatura->tipo == 'proforma' ? 'selected' : '' }}>Proforma</option>
                <option value="fatura" {{ $fatura->tipo == 'fatura' ? 'selected' : '' }}>Fatura</option>
                <option value="recibo" {{ $fatura->tipo == 'recibo' ? 'selected' : '' }}>Recibo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Fatura</button>
        <a href="{{ route('tenant.faturas.index', ['tenant'=>app('tenant')->subdomain]) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
