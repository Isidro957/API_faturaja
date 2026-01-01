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
*/
Route::post('/login', [ApiAuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| ROTAS DO TENANT (multi-tenant)
|--------------------------------------------------------------------------
*/
Route::middleware([ResolveTenant::class])->group(function () {

    // Rotas protegidas
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

        // USERS
        Route::apiResource('users', ApiTenantUserController::class);

        // CLIENTES
        Route::apiResource('clientes', ApiClienteController::class);

        // PRODUTOS
        Route::apiResource('produtos', ApiProdutoController::class);

        // FATURAS
        Route::apiResource('faturas', ApiFaturaController::class);

        // ITENS DE FATURA
        Route::apiResource('faturas/{fatura}/itens', ApiItemFaturaController::class);

        // PAGAMENTOS
        Route::apiResource('faturas/{fatura}/pagamentos', ApiPagamentoController::class);
    });

});
