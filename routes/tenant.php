<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\TenantUserController;
use App\Http\Controllers\ClienteWebController;
use App\Http\Controllers\ProdutoWebController;
use App\Http\Controllers\FaturaWebController;
use App\Http\Controllers\ItemFaturaController;
use App\Http\Controllers\PagamentoWebController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
| Rotas disponíveis apenas quando um tenant estiver ativo.
| Middleware InitializeTenancyByDomain garante a conexão correta
| com o banco de dados do tenant.
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Dashboard do tenant
    Route::get('/', fn() => view('tenant.dashboard'))->name('tenant.dashboard');

    // Autenticação do tenant
    Route::prefix('auth')->group(function () {
        Route::get('/login', fn() => view('tenant.auth.login'))->name('tenant.login');
        Route::get('/register', fn() => view('tenant.auth.register'))->name('tenant.register');
        Route::post('/register', [TenantAuthController::class, 'register']);
        Route::post('/login', [TenantAuthController::class, 'login']);
        Route::middleware('auth:tenant')->post('/logout', [TenantAuthController::class, 'logout']);
    });

    // Rotas protegidas (usuário logado)
    Route::middleware(['auth:tenant', 'tenant.user'])->group(function () {

        /*
        |-----------------
        | USUÁRIOS
        |-----------------
        */
        Route::resource('users', TenantUserController::class, [
            'as' => 'tenant'
        ]);

        /*
        |-----------------
        | CLIENTES
        |-----------------
        */
        Route::prefix('clients')->name('tenant.clients.')->group(function () {
            Route::get('/', [ClienteWebController::class, 'index'])->name('index');
            Route::get('/create', [ClienteWebController::class, 'create'])->name('create');
            Route::post('/', [ClienteWebController::class, 'store'])->name('store');
            Route::get('/{client}/edit', [ClienteWebController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClienteWebController::class, 'update'])->name('update');
            Route::delete('/{client}', [ClienteWebController::class, 'destroy'])->name('destroy');
        });

        /*
        |-----------------
        | PRODUTOS
        |-----------------
        */
        Route::prefix('produtos')->name('tenant.produtos.')->group(function () {
            Route::get('/', [ProdutoWebController::class, 'index'])->name('index');
            Route::get('create', [ProdutoWebController::class, 'create'])->name('create');
            Route::post('/', [ProdutoWebController::class, 'store'])->name('store');
            Route::get('{produto}/edit', [ProdutoWebController::class, 'edit'])->name('edit');
            Route::put('{produto}', [ProdutoWebController::class, 'update'])->name('update');
            Route::delete('{produto}', [ProdutoWebController::class, 'destroy'])->name('destroy');
        });

        /*
        |-----------------
        | FATURAS
        |-----------------
        */
        Route::prefix('faturas')->name('tenant.faturas.')->group(function () {
            Route::get('/', [FaturaWebController::class, 'index'])->name('index');
            Route::get('create', [FaturaWebController::class, 'create'])->name('create');
            Route::post('/', [FaturaWebController::class, 'store'])->name('store');
            Route::get('{fatura}/edit', [FaturaWebController::class, 'edit'])->name('edit');
            Route::put('{fatura}', [FaturaWebController::class, 'update'])->name('update');
            Route::delete('{fatura}', [FaturaWebController::class, 'destroy'])->name('destroy');

            /*
            |-----------------
            | ITENS DA FATURA
            |-----------------
            */
            Route::prefix('{fatura}/itens')->name('tenant.itens_fatura.')->group(function () {
                Route::get('/', [ItemFaturaController::class, 'index'])->name('index');
                Route::get('/create', [ItemFaturaController::class, 'create'])->name('create');
                Route::post('/', [ItemFaturaController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [ItemFaturaController::class, 'edit'])->name('edit');
                Route::put('/{id}', [ItemFaturaController::class, 'update'])->name('update');
                Route::delete('/{id}', [ItemFaturaController::class, 'destroy'])->name('destroy');
            });

            /*
            |-----------------
            | PAGAMENTOS
            |-----------------
            */
            Route::prefix('{fatura}/pagamentos')->name('tenant.pagamentos.')->group(function () {
                Route::get('/', [PagamentoWebController::class, 'index'])->name('index');
                Route::get('/create', [PagamentoWebController::class, 'create'])->name('create');
                Route::post('/', [PagamentoWebController::class, 'store'])->name('store');
                Route::get('/{pagamento}/edit', [PagamentoWebController::class, 'edit'])->name('edit');
                Route::put('/{pagamento}', [PagamentoWebController::class, 'update'])->name('update');
                Route::delete('/{pagamento}', [PagamentoWebController::class, 'destroy'])->name('destroy');
            });
        });
    });
});