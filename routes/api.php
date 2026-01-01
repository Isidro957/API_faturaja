<?php


use App\Http\Middleware\ResolveTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * LOGIN GLOBAL
 */
Route::post('/login', [\App\Http\Controllers\ApiAuthController::class, 'login']);

/**
 * ROTAS DO TENANT (multi-tenant)
 */
Route::middleware([ResolveTenant::class])->group(function () {

    // Registro opcional
    Route::post('/register', [\App\Http\Controllers\ApiAuthController::class, 'register']);

    // Rotas protegidas
    Route::middleware(['auth:sanctum', 'tenant.user'])->group(function () {

        Route::get('/tenant-info', function (Request $request) {
            return response()->json([
                'tenant' => app('tenant'),
                'user'   => $request->user(),
            ]);
        });

        Route::post('/logout', [\App\Http\Controllers\ApiAuthController::class, 'logout']);

        Route::apiResource('/users', \App\Http\Controllers\TenantUserController::class);
    });
});
