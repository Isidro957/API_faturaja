@extends('tenant.layouts.app')

@section('content')
<div class="container">
    <h1>Itens da Fatura #{{ $fatura->numero }}</h1>

    <a href="{{ route('tenant.faturas.itens.create', $fatura->id) }}" class="btn btn-primary mb-3">Adicionar Item</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Valor Unitário</th>
                <th>Desconto Unitário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->produto?->nome ?? '—' }}</td>
                <td>{{ $item->descricao }}</td>
                <td>{{ $item->quantidade }}</td>
                <td>{{ number_format($item->valor_unitario,2,',','.') }}</td>
                <td>{{ number_format($item->valor_desconto_unitario,2,',','.') }}</td>
                <td>
                    <a href="{{ route('tenant.faturas.itens.edit', [$fatura->id, $item->id]) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('tenant.faturas.itens.destroy', [$fatura->id, $item->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente deletar este item?')">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
