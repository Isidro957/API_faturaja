@extends('tenant.layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Faturas</h1>
    <a href="{{ route('tenant.faturas.create', ['tenant' => app('tenant')->subdomain]) }}" class="btn btn-success mb-3">
        Criar Fatura
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>NIF</th>
                <th>Número</th>
                <th>Data Emissão</th>
                <th>Data Vencimento</th>
                <th>Valor Total</th>
                <th>Status</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($faturas as $fatura)
            <tr>
                <td>{{ $fatura->id }}</td>
                <td>{{ $fatura->cliente->nome ?? '—' }}</td>
                <td>{{ $fatura->nif_cliente }}</td>
                <td>{{ $fatura->numero }}</td>
                <td>{{ $fatura->data_emissao }}</td>
                <td>{{ $fatura->data_vencimento }}</td>
                <td>{{ number_format($fatura->valor_total, 2, ',', '.') }}</td>
                <td>{{ ucfirst($fatura->status) }}</td>
                <td>{{ ucfirst($fatura->tipo) }}</td>
                <td>
                    <a href="{{ route('tenant.faturas.edit', ['tenant'=>app('tenant')->subdomain, 'id'=>$fatura->id]) }}" class="btn btn-primary btn-sm">Editar</a>
                    <form action="{{ route('tenant.faturas.destroy', ['tenant'=>app('tenant')->subdomain, 'id'=>$fatura->id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar esta fatura?')">Deletar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Nenhuma fatura encontrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
