@extends('tenant.layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Produtos</h3>
        <a href="{{ route('tenant.produtos.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Novo Produto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                <tr>
                    <td>{{ $produto->nome }}</td>
                    <td>{{ $produto->descricao }}</td>
                    <td>{{ number_format($produto->preco, 2, ',', '.') }} Kz</td>
                    <td>{{ $produto->estoque }}</td>
                    <td>{{ ucfirst($produto->tipo) }}</td>
                    <td>
                        <a href="{{ route('tenant.produtos.edit', $produto->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>

                        <form action="{{ route('tenant.produtos.destroy', $produto->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                <i class="bi bi-trash-fill"></i> Excluir
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
