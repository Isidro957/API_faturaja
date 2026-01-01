<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\TenantUserController;
use App\Http\Controllers\ClienteWebController;
use App\Http\Controllers\ProdutoWebController;
use App\Http\Controllers\FaturaWebController;
use App\Http\Controllers\ItemFaturaController;
use App\Http\Controllers\PagamentoWebController;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| VERIFICAÇÃO DE EMAIL
|--------------------------------------------------------------------------
*/

// Notificação de email não verificado
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:tenant')->name('verification.notice');

// Verificar link de email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $user = \App\Models\TenantUser::findOrFail($request->route('id'));
    Auth::guard('tenant')->login($user);
    $request->fulfill();
    return redirect()->route('tenant.dashboard');
})->middleware(['signed'])->name('verification.verify');

// Reenviar link de verificação
Route::post('/email/verification-notification', function (Request $request) {
    $user = $request->user('tenant');
    $user->sendEmailVerificationNotification();
    return back()->with('success', 'Link de verificação enviado!');
})->middleware(['auth:tenant', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| LANDLORD (GLOBAL LOGIN/REGISTER)
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'));

Route::get('/login', [TenantAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [TenantAuthController::class, 'login']);

Route::get('/register', [TenantAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [TenantAuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| TENANT (SUBDOMÍNIO)
|--------------------------------------------------------------------------
*/
Route::domain('{tenant}.faturaja.sdoca')
    ->middleware(ResolveTenant::class)
    ->group(function () {

        // Login via subdomínio
        Route::get('/authenticate', function (Request $request) {
            if (!Auth::guard('tenant')->attempt($request->only('email', 'password'))) {
                abort(401, 'Credenciais inválidas');
            }
            $request->session()->regenerate();
            return redirect()->route('tenant.dashboard');
        });

        // Rotas protegidas
        Route::middleware(['auth:tenant', 'tenant.user'])->group(function () {

            // Dashboard
            Route::get('/dashboard', fn() => view('tenant.dashboard'))->name('tenant.dashboard');

            /*
            |-----------------
            | USERS
            |-----------------
            */
            Route::prefix('users')->name('tenant.users.')->group(function () {
                Route::get('/', [TenantUserController::class, 'index'])->name('index');
                Route::get('/create', [TenantUserController::class, 'create'])->name('create');
                Route::post('/', [TenantUserController::class, 'store'])->name('store');
                Route::get('/{user}/edit', [TenantUserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [TenantUserController::class, 'update'])->name('update');
                Route::delete('/{user}', [TenantUserController::class, 'destroy'])->name('destroy');
            });

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
                Route::get('/create', [ProdutoWebController::class, 'create'])->name('create');
                Route::post('/', [ProdutoWebController::class, 'store'])->name('store');
                Route::get('/{produto}/edit', [ProdutoWebController::class, 'edit'])->name('edit');
                Route::put('/{produto}', [ProdutoWebController::class, 'update'])->name('update');
                Route::delete('/{produto}', [ProdutoWebController::class, 'destroy'])->name('destroy');
            });

            /*
            |-----------------
            | FATURAS
            |-----------------
            */
            Route::prefix('faturas')->name('tenant.faturas.')->group(function () {
                Route::get('/', [FaturaWebController::class, 'index'])->name('index');
                Route::get('/create', [FaturaWebController::class, 'create'])->name('create');
                Route::post('/', [FaturaWebController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [FaturaWebController::class, 'edit'])->name('edit');
                Route::put('/{id}', [FaturaWebController::class, 'update'])->name('update');
                Route::delete('/{id}', [FaturaWebController::class, 'destroy'])->name('destroy');

                // Itens da fatura
                Route::prefix('{fatura}/itens')->name('tenant.itens_fatura.')->group(function () {
                    Route::get('/', [ItemFaturaController::class, 'index'])->name('index');
                    Route::get('/create', [ItemFaturaController::class, 'create'])->name('create');
                    Route::post('/', [ItemFaturaController::class, 'store'])->name('store');
                    Route::get('/{id}/edit', [ItemFaturaController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [ItemFaturaController::class, 'update'])->name('update');
                    Route::delete('/{id}', [ItemFaturaController::class, 'destroy'])->name('destroy');
                });

                // Pagamentos de uma fatura
                Route::prefix('{fatura}/pagamentos')->name('tenant.faturas.pagamentos.')->group(function () {
                    Route::get('/', [PagamentoWebController::class, 'index'])->name('index');
                    Route::get('/create', [PagamentoWebController::class, 'create'])->name('create');
                    Route::post('/', [PagamentoWebController::class, 'store'])->name('store');
                    Route::get('/{id}/edit', [PagamentoWebController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [PagamentoWebController::class, 'update'])->name('update');
                    Route::delete('/{id}', [PagamentoWebController::class, 'destroy'])->name('destroy');
                });
            });

            // Pagamentos globais do tenant (dashboard)
            Route::get('pagamentos', [PagamentoWebController::class, 'all'])->name('tenant.pagamentos.index');

            // Logout tenant
            Route::post('/logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');
        });
    });
