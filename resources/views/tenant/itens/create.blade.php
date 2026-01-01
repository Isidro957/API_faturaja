@extends('tenant.layouts.app')

@section('content')
<div class="container">
    <h1>Adicionar Item à Fatura #{{ $fatura->numero }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('tenant.faturas.itens.store', $fatura->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Produto (opcional)</label>
            <select name="produto_id" class="form-control">
                <option value="">Selecione um produto</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Descrição</label>
            <input type="text" name="descricao" class="form-control" value="{{ old('descricao') }}">
        </div>

        <div class="mb-3">
            <label>Quantidade</label>
            <input type="number" name="quantidade" class="form-control" value="{{ old('quantidade',1) }}" required>
        </div>

        <div class="mb-3">
            <label>Valor Unitário</label>
            <input type="number" name="valor_unitario" step="0.01" class="form-control" value="{{ old('valor_unitario',0) }}" required>
        </div>

        <div class="mb-3">
            <label>Desconto Unitário</label>
            <input type="number" name="valor_desconto_unitario" step="0.01" class="form-control" value="{{ old('valor_desconto_unitario',0) }}" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('tenant.faturas.itens.index', $fatura->id) }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
