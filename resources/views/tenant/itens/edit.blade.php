@extends('tenant.layouts.app')

@section('content')
<div class="container">
    <h1>Editar Item da Fatura #{{ $fatura->numero }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('tenant.faturas.itens.update', [$fatura->id, $item->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Produto (opcional)</label>
            <select name="produto_id" class="form-control">
                <option value="">Selecione um produto</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}" {{ $item->produto_id == $produto->id ? 'selected' : '' }}>{{ $produto->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Descrição</label>
            <input type="text" name="descricao" class="form-control" value="{{ old('descricao', $item->descricao) }}">
        </div>

        <div class="mb-3">
            <label>Quantidade</label>
            <input type="number" name="quantidade" class="form-control" value="{{ old('quantidade', $item->quantidade) }}" required>
        </div>

        <div class="mb-3">
            <label>Valor Unitário</label>
            <input type="number" name="valor_unitario" step="0.01" class="form-control" value="{{ old('valor_unitario', $item->valor_unitario) }}" required>
        </div>

        <div class="mb-3">
            <label>Desconto Unitário</label>
            <input type="number" name="valor_desconto_unitario" step="0.01" class="form-control" value="{{ old('valor_desconto_unitario', $item->valor_desconto_unitario) }}" required>
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('tenant.faturas.itens.index', $fatura->id) }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
