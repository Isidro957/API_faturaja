<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pagamento extends TenantModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'fatura_id',
        'data_pagamento',
        'valor_pago',
        'valor_troco',
        'valor_total_desconto',
        'metodo_pagamento',
        'status',
    ];

    public function fatura()
    {
        return $this->belongsTo(Fatura::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeDoTenant($query)
    {
        return $query->where('tenant_id', app('tenant')->id);
    }
}
