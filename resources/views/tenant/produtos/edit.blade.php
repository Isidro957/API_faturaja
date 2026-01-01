@extends('tenant.layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Editar Produto</h3>

    <form action="{{ route('tenant.produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $produto->nome) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $produto->descricao) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="number" name="preco" class="form-control" step="0.01" value="{{ old('preco', $produto->preco) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estoque</label>
            <input type="number" name="estoque" class="form-control" value="{{ old('estoque', $produto->estoque) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="">-- Selecionar --</option>
                <option value="produto" {{ old('tipo', $produto->tipo) == 'produto' ? 'selected' : '' }}>Produto</option>
                <option value="servico" {{ old('tipo', $produto->tipo) == 'servico' ? 'selected' : '' }}>Serviço</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-fill"></i> Atualizar Produto</button>
        <a href="{{ route('tenant.produtos.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>

</div>
@endsection
