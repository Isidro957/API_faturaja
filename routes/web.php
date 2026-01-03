<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\TenantUserController;
use App\Http\Controllers\ClienteWebController;
use App\Http\Controllers\ProdutoWebController;
use App\Http\Controllers\FaturaWebController;
use App\Http\Controllers\ItemFaturaController;
use App\Http\Controllers\PagamentoWebController;
use App\Http\Middleware\ResolveTenant;

/*
|--------------------------------------------------------------------------
| LANDLORD DOMAIN (faturaja.sdoca)
|--------------------------------------------------------------------------
*/

Route::domain('faturaja.sdoca')->group(function () {

    // Página inicial
    Route::view('/', 'home')->name('home');

    // Página de boas-vindas
    Route::view('/welcome', 'welcome')->name('welcome');

    // Autenticação global
    Route::get('/login', [TenantAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TenantAuthController::class, 'login']);

    Route::get('/register', [TenantAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [TenantAuthController::class, 'register']);

    // Verificação de email (tenant)
    Route::middleware('auth:tenant')->group(function () {
        Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice');

        Route::post('/email/verification-notification', function (Request $request) {
            $request->user('tenant')->sendEmailVerificationNotification();
            return back()->with('success', 'Link de verificação enviado!');
        })->middleware('throttle:6,1')->name('verification.send');
    });

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $user = \App\Models\TenantUser::findOrFail($request->route('id'));
        Auth::guard('tenant')->login($user);
        $request->fulfill();
        return redirect()->route('tenant.dashboard');
    })->middleware('signed')->name('verification.verify');
});

/*
|--------------------------------------------------------------------------
| TENANT DOMAIN ({tenant}.faturaja.sdoca)
|--------------------------------------------------------------------------
*/
Route::domain('{tenant}.faturaja.sdoca')
    ->middleware(ResolveTenant::class)
    ->group(function () {

        // Autenticação via subdomínio
        Route::post('/authenticate', function (Request $request) {
            if (!Auth::guard('tenant')->attempt($request->only('email', 'password'))) {
                abort(401, 'Credenciais inválidas');
            }
            $request->session()->regenerate();
            return redirect()->route('tenant.dashboard');
        })->name('tenant.authenticate');

        // Rotas protegidas do tenant
        Route::middleware(['auth:tenant', 'tenant.user'])->group(function () {

            // Dashboard
            Route::view('/dashboard', 'tenant.dashboard')->name('tenant.dashboard');

            // Usuários
            Route::prefix('users')->name('tenant.users.')->group(function () {
                Route::get('/', [TenantUserController::class, 'index'])->name('index');
                Route::get('/create', [TenantUserController::class, 'create'])->name('create');
                Route::post('/', [TenantUserController::class, 'store'])->name('store');
                Route::get('/{user}/edit', [TenantUserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [TenantUserController::class, 'update'])->name('update');
                Route::delete('/{user}', [TenantUserController::class, 'destroy'])->name('destroy');
            });

            // Clientes
            Route::prefix('clients')->name('tenant.clients.')->group(function () {
                Route::get('/', [ClienteWebController::class, 'index'])->name('index');
                Route::get('/create', [ClienteWebController::class, 'create'])->name('create');
                Route::post('/', [ClienteWebController::class, 'store'])->name('store');
                Route::get('/{client}/edit', [ClienteWebController::class, 'edit'])->name('edit');
                Route::put('/{client}', [ClienteWebController::class, 'update'])->name('update');
                Route::delete('/{client}', [ClienteWebController::class, 'destroy'])->name('destroy');
            });

            // Produtos
            Route::prefix('produtos')->name('tenant.produtos.')->group(function () {
                Route::get('/', [ProdutoWebController::class, 'index'])->name('index');
                Route::get('/create', [ProdutoWebController::class, 'create'])->name('create');
                Route::post('/', [ProdutoWebController::class, 'store'])->name('store');
                Route::get('/{produto}/edit', [ProdutoWebController::class, 'edit'])->name('edit');
                Route::put('/{produto}', [ProdutoWebController::class, 'update'])->name('update');
                Route::delete('/{produto}', [ProdutoWebController::class, 'destroy'])->name('destroy');
            });

            // Faturas
            Route::prefix('faturas')->name('tenant.faturas.')->group(function () {
                Route::get('/', [FaturaWebController::class, 'index'])->name('index');
                Route::get('/create', [FaturaWebController::class, 'create'])->name('create');
                Route::post('/', [FaturaWebController::class, 'store'])->name('store');
                Route::get('/{fatura}/edit', [FaturaWebController::class, 'edit'])->name('edit');
                Route::put('/{fatura}', [FaturaWebController::class, 'update'])->name('update');
                Route::delete('/{fatura}', [FaturaWebController::class, 'destroy'])->name('destroy');

                // Itens da fatura
                Route::prefix('{fatura}/itens')->name('itens.')->group(function () {
                    Route::get('/', [ItemFaturaController::class, 'index'])->name('index');
                    Route::get('/create', [ItemFaturaController::class, 'create'])->name('create');
                    Route::post('/', [ItemFaturaController::class, 'store'])->name('store');
                    Route::get('/{item}/edit', [ItemFaturaController::class, 'edit'])->name('edit');
                    Route::put('/{item}', [ItemFaturaController::class, 'update'])->name('update');
                    Route::delete('/{item}', [ItemFaturaController::class, 'destroy'])->name('destroy');
                });

                // Pagamentos da fatura
                Route::prefix('{fatura}/pagamentos')->name('pagamentos.')->group(function () {
                    Route::get('/', [PagamentoWebController::class, 'index'])->name('index');
                    Route::get('/create', [PagamentoWebController::class, 'create'])->name('create');
                    Route::post('/', [PagamentoWebController::class, 'store'])->name('store');
                    Route::get('/{pagamento}/edit', [PagamentoWebController::class, 'edit'])->name('edit');
                    Route::put('/{pagamento}', [PagamentoWebController::class, 'update'])->name('update');
                    Route::delete('/{pagamento}', [PagamentoWebController::class, 'destroy'])->name('destroy');
                });
            });

            // Pagamentos globais
            Route::get('/pagamentos', [PagamentoWebController::class, 'all'])->name('tenant.pagamentos.index');

            // Logout
            Route::post('/logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');
        });
    });
