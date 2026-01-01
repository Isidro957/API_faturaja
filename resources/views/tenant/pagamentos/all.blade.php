@extends('tenant.layout')

@section('content')
<div class="container">
    <h2>Todos os Pagamentos</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fatura</th>
                <th>Valor Pago</th>
                <th>Data</th>
                <th>Método</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagamentos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->fatura_id }}</td>
                <td>{{ $p->valor_pago }}</td>
                <td>{{ $p->data_pagamento }}</td>
                <td>{{ $p->metodo_pagamento }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
