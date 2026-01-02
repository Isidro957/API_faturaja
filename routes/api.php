<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Http\Request;

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiTenantUserController;
use App\Http\Controllers\ApiClienteController;
use App\Http\Controllers\ApiProdutoController;
use App\Http\Controllers\ApiFaturaController;
use App\Http\Controllers\ApiItemFaturaController;
use App\Http\Controllers\ApiPagamentoController;

/*
|--------------------------------------------------------------------------
| LOGIN GLOBAL
|--------------------------------------------------------------------------
| Rota pública para login de qualquer usuário (descobre tenant pelo email)
*/
Route::post('/login', [ApiAuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| ROTAS DO TENANT (multi-tenant)
|--------------------------------------------------------------------------
| Todas as rotas abaixo precisam que o tenant seja resolvido
*/
Route::middleware([ResolveTenant::class])->group(function () {

    // Rotas protegidas → usuário autenticado + pertence ao tenant
    Route::middleware(['auth:sanctum', 'tenant.user'])->group(function () {

        // Informações do tenant + usuário logado
        Route::get('/tenant-info', function (Request $request) {
            return response()->json([
                'tenant' => app('tenant'),
                'user'   => $request->user(),
            ]);
        });

        // Logout
        Route::post('/logout', [ApiAuthController::class, 'logout']);

        /*
        |--------------------------------------------------------------------------
        | USUÁRIOS DO TENANT
        |--------------------------------------------------------------------------
        | Payload exemplo para store/update:
        | {
        |   "name": "João",
        |   "email": "joao@tenant.com",
        |   "password": "123456",
        |   "password_confirmation": "123456",
        |   "role": "admin"
        | }
        */
        Route::apiResource('users', ApiTenantUserController::class);

        /*
        |--------------------------------------------------------------------------
        | CLIENTES
        |--------------------------------------------------------------------------
        | Payload exemplo para store/update:
        | {
        |   "nome": "Cliente X",
        |   "email": "cliente@tenant.com",
        |   "telefone": "912345678"
        | }
        */
        Route::apiResource('clientes', ApiClienteController::class);

        /*
        |--------------------------------------------------------------------------
        | PRODUTOS
        |--------------------------------------------------------------------------
        | Payload exemplo para store/update:
        | {
        |   "nome": "Produto A",
        |   "descricao": "Descrição",
        |   "preco": 100.0,
        |   "estoque": 50,
        |   "tipo": "produto"
        | }
        */
        Route::apiResource('produtos', ApiProdutoController::class);

        /*
        |--------------------------------------------------------------------------
        | FATURAS
        |--------------------------------------------------------------------------
        | Payload exemplo para store/update:
        | {
        |   "cliente_id": "uuid-do-cliente",
        |   "nif_cliente": "123456789",
        |   "numero": "FAT-001",
        |   "data_emissao": "2026-01-02",
        |   "data_vencimento": "2026-01-15",
        |   "valor_total": 500.0,
        |   "status": "pendente",
        |   "tipo": "fatura"
        | }
        */
        Route::apiResource('faturas', ApiFaturaController::class);

        /*
        |--------------------------------------------------------------------------
        | ITENS DE FATURA
        |--------------------------------------------------------------------------
        | Parâmetro: {fatura_id}
        | Payload exemplo para store/update:
        | {
        |   "produto_id": "uuid-do-produto",
        |   "descricao": "Item descrição",
        |   "quantidade": 2,
        |   "valor_unitario": 100.0,
        |   "valor_desconto_unitario": 0
        | }
        */
        Route::apiResource('faturas/{fatura_id}/itens', ApiItemFaturaController::class);

        /*
        |--------------------------------------------------------------------------
        | PAGAMENTOS DE FATURA
        |--------------------------------------------------------------------------
        | Parâmetro: {fatura_id}
        | Payload exemplo para store/update:
        | {
        |   "data_pagamento": "2026-01-02",
        |   "valor_pago": 500.0,
        |   "valor_troco": 0,
        |   "valor_total_desconto": 0,
        |   "metodo_pagamento": "pix"
        | }
        */
        Route::apiResource('faturas/{fatura_id}/pagamentos', ApiPagamentoController::class);

        /*
        |--------------------------------------------------------------------------
        | LISTAR TODOS PAGAMENTOS DO TENANT
        |--------------------------------------------------------------------------
        */
        Route::get('/pagamentos/all', [ApiPagamentoController::class, 'all']);
    });
});
