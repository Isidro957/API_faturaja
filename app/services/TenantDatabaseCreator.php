<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TenantDatabaseCreator
{
    public static function createDatabase(string $databaseName)
    {
        DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    }
}
