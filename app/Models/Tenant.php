<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'name', 
        'subdomain', 
        'database_name', 
        'data'
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

        public function users()
    {
        // TenantUser é o modelo que existe em cada banco do tenant
        return $this->hasMany(TenantUser::class, 'tenant_id', 'id');
    }
}
