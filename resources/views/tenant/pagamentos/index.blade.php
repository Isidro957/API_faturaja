@extends('tenant.layouts.app')

@section('content')

<div class="container py-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    @if(isset($fatura))
        <h2>Pagamentos da Fatura #{{ $fatura->numero }}</h2>
        <a href="{{ route('tenant.pagamentos.create', $fatura->id) }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Adicionar Pagamento
        </a>
    @else
        <h2>Todos os Pagamentos do Tenant</h2>
        <button class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Adicionar Pagamento
        </button>
    @endif
</div>
   <!-- Resumo rápido -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total de Pagamentos</h5>
                    <p class="display-6">{{ $pagamentos->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Valor Total Pago</h5>
                    <p class="display-6">Kz {{ number_format($pagamentos->sum('valor_pago'), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total de Descontos</h5>
                    <p class="display-6">Kz {{ number_format($pagamentos->sum('valor_total_desconto'), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de pagamentos -->
    <div class="card shadow-sm p-3">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    @if(!isset($fatura))
                        <th>Fatura</th>
                    @endif
                    <th>Data Pagamento</th>
                    <th>Valor Pago</th>
                    <th>Troco</th>
                    <th>Desconto</th>
                    <th>Método</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagamentos as $pagamento)
                <tr>
                    @if(!isset($fatura))
                        <td>#{{ $pagamento->fatura->numero ?? 'N/A' }}</td>
                    @endif
                    <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }}</td>
                    <td>Kz {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</td>
                    <td>Kz {{ number_format($pagamento->valor_troco ?? 0, 2, ',', '.') }}</td>
                    <td>Kz {{ number_format($pagamento->valor_total_desconto ?? 0, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($pagamento->metodo_pagamento) }}</td>
                    <td class="d-flex gap-1">
                        @if(isset($fatura))
                            <a href="{{ route('tenant.faturas.pagamentos.edit', [$fatura->id, $pagamento->id]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('tenant.faturas.pagamentos.destroy', [$fatura->id, $pagamento->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i> Excluir
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ isset($fatura) ? 7 : 8 }}" class="text-center">Nenhum pagamento encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection