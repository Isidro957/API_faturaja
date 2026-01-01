@extends('tenant.layouts.app')

@section('title', 'Novo Cliente')

@section('content')
<div class="container mt-4">
    <h1>Novo Cliente</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenant.clients.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" id="nome" value="{{ old('nome') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" id="telefone" value="{{ old('telefone') }}">
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('tenant.clients.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
