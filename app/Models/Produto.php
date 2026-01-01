<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Produto extends TenantModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'nome',
        'descricao',
        'preco',
        'estoque',
        'tipo',
    ];

    // Gera UUID automaticamente antes de criar
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::uuid();
            }
        });
    }

    // Relacionamento com tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Escopo para filtrar apenas produtos do tenant atual
    public function scopeDoTenant($query)
    {
        return $query->where('tenant_id', app('tenant')->id);
    }
}
