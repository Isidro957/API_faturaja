<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemFatura extends TenantModel
{
    use HasFactory;

    protected $table = 'itens_fatura';

    protected $fillable = [
        'fatura_id',
        'produto_id',
        'descricao',
        'quantidade',
        'valor_unitario',
        'valor_desconto_unitario'
    ];

    public function fatura()
    {
        return $this->belongsTo(Fatura::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    // Valor total do item considerando desconto
    public function getTotalAttribute()
    {
        return ($this->valor_unitario - $this->valor_desconto_unitario) * $this->quantidade;
    }
    public function scopeDoTenant($query)
{
    return $query->where('tenant_id', app('tenant')->id);
}

}
