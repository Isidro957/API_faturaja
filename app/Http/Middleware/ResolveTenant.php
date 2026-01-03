<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        /*
        |--------------------------------------------------------------------------
        | IGNORAR landlord (faturaja.sdoca)
        |--------------------------------------------------------------------------
        | Se o host for exatamente faturaja.sdoca, não tentamos resolver tenant.
        */
        if ($host === 'faturaja.sdoca') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | NÃO resolver tenant no login/register global
        |--------------------------------------------------------------------------
        */
        if ($request->is('login') || $request->is('register')) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIENTE LOCAL (subdomínios .faturaja.sdoca)
        |--------------------------------------------------------------------------
        */
        if (str_ends_with($host, '.faturaja.sdoca')) {
            $parts = explode('.', $host);

            // subdomínio é sempre a primeira parte
            $subdomain = $parts[0];

            $tenant = Tenant::where('subdomain', $subdomain)->first();

            if (! $tenant) {
                abort(404, "Tenant '{$subdomain}' não existe.");
            }

            $this->bootstrapTenant($tenant);

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUÇÃO (empresa.dominio.com)
        |--------------------------------------------------------------------------
        */
        $parts = explode('.', $host);

        if (count($parts) < 3) {
            // sem subdomínio → não é tenant
            return $next($request);
        }

        $subdomain = $parts[0];

        if ($subdomain === 'www') {
            return $next($request);
        }

        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (! $tenant) {
            abort(404, "Tenant '{$subdomain}' não existe.");
        }

        $this->bootstrapTenant($tenant);

        return $next($request);
    }

    /*
    |--------------------------------------------------------------------------
    | BOOTSTRAP DO TENANT
    |--------------------------------------------------------------------------
    */
    private function bootstrapTenant(Tenant $tenant): void
    {
        // Base de dados do tenant
        config([
            'database.connections.tenant.database' => $tenant->database_name,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // Disponível globalmente
        app()->instance('tenant', $tenant);

        // Parâmetro automático nas rotas
        URL::defaults([
            'tenant' => $tenant->subdomain,
        ]);
    }
}
