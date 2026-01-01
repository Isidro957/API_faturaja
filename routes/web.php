<?php   

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\TenantUserController;
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
    return view('auth.verify-email'); // Blade que você vai criar
})->middleware('auth:tenant')->name('verification.notice');

// Verificar link de email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    // Autentica o usuário automaticamente no guard tenant
    $user = \App\Models\TenantUser::findOrFail($request->route('id'));
    Auth::guard('tenant')->login($user);

    $request->fulfill(); // Marca email_verified_at

    return redirect()->route('tenant.dashboard');
})->middleware(['signed'])->name('verification.verify');

// Reenviar link de verificação
Route::post('/email/verification-notification', function (Request $request) {
    $user = $request->user('tenant'); // pega usuário do guard tenant
    $user->sendEmailVerificationNotification();

    return back()->with('success', 'Link de verificação enviado!');
})->middleware(['auth:tenant', 'throttle:6,1'])->name('verification.send');


/*
|--------------------------------------------------------------------------
| LANDLORD (GLOBAL LOGIN/REGISTER)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

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

        // Login via subdomínio (tenant)
        Route::get('/authenticate', function (Request $request) {
            if (!Auth::guard('tenant')->attempt($request->only('email', 'password'))) {
                abort(401, 'Credenciais inválidas');
            }

            $request->session()->regenerate();

            return redirect()->route('tenant.dashboard');
        });

        // Rotas protegidas
        Route::middleware(['auth:tenant', 'tenant.user'])->group(function () {

            Route::get('/dashboard', function () {
                return view('tenant.dashboard');
            })->name('tenant.dashboard');

            Route::prefix('users')->name('tenant.users.')->group(function () {
                Route::get('/', [TenantUserController::class, 'index'])->name('index');
                Route::get('/create', [TenantUserController::class, 'create'])->name('create');
                Route::post('/', [TenantUserController::class, 'store'])->name('store');
                Route::get('/{user}/edit', [TenantUserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [TenantUserController::class, 'update'])->name('update');
                Route::delete('/{user}', [TenantUserController::class, 'destroy'])->name('destroy');
            });

            Route::post('/logout', [TenantAuthController::class, 'logout'])
                ->name('tenant.logout');
        });
    });
