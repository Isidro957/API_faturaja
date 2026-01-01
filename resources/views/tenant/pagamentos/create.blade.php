@extends('tenant.layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Adicionar Pagamento - Fatura #{{ $fatura->numero }}</h2>
        <a href="{{ route('tenant.pagamentos.index', $fatura->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('tenant.pagamentos.store', $fatura->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                <input type="date" class="form-control @error('data_pagamento') is-invalid @enderror" 
                       name="data_pagamento" id="data_pagamento" value="{{ old('data_pagamento') }}" required>
                @error('data_pagamento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="valor_pago" class="form-label">Valor Pago</label>
                <input type="number" step="0.01" class="form-control @error('valor_pago') is-invalid @enderror"
                       name="valor_pago" id="valor_pago" placeholder="Ex: 1500,00" value="{{ old('valor_pago') }}" required>
                @error('valor_pago')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="valor_troco" class="form-label">Troco</label>
                <input type="number" step="0.01" class="form-control @error('valor_troco') is-invalid @enderror"
                       name="valor_troco" id="valor_troco" placeholder="0,00" value="{{ old('valor_troco') }}">
                @error('valor_troco')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="valor_total_desconto" class="form-label">Desconto Total</label>
                <input type="number" step="0.01" class="form-control @error('valor_total_desconto') is-invalid @enderror"
                       name="valor_total_desconto" id="valor_total_desconto" placeholder="0,00" value="{{ old('valor_total_desconto') }}">
                @error('valor_total_desconto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                <select class="form-select @error('metodo_pagamento') is-invalid @enderror" 
                        name="metodo_pagamento" id="metodo_pagamento" required>
                    <option value="">Selecione...</option>
                    <option value="boleto" {{ old('metodo_pagamento')=='boleto' ? 'selected' : '' }}>Boleto</option>
                    <option value="cartão" {{ old('metodo_pagamento')=='cartão' ? 'selected' : '' }}>Cartão</option>
                    <option value="pix" {{ old('metodo_pagamento')=='pix' ? 'selected' : '' }}>Pix</option>
                </select>
                @error('metodo_pagamento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Salvar Pagamento
                </button>
                <a href="{{ route('tenant.pagamentos.index', $fatura->id) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
