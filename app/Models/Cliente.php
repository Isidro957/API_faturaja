<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Cliente extends TenantModel
{
    use HasFactory;

    protected $connection = 'tenant';
    protected $table = 'clientes';

    // Indica que o id não é auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',        // agora o UUID será preenchido automaticamente
        'tenant_id',
        'nome',
        'email',
        'telefone',
    ];

    protected $casts = [
        'id' => 'string',
        'tenant_id' => 'string',
    ];

    // Gerar UUID automaticamente ao criar o cliente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
        public function scopeDoTenant($query)
    {
        return $query->where('tenant_id', app('tenant')->id);
    }
}
