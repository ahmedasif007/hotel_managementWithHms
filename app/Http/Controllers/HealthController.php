<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function check()
    {
        try {
            // Check database connection
            DB::connection()->getPdo();
            $database = 'OK';
        } catch (\Exception $e) {
            $database = 'FAILED: ' . $e->getMessage();
        }

        return response()->json([
            'status' => 'OK',
            'app' => [
                'name' => config('app.name'),
                'env' => config('app.env'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
            ],
            'database' => $database,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function detailed()
    {
        return response()->json([
            'system' => [
                'php_version' => phpversion(),
                'laravel_version' => app()->version(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'timezone' => date_default_timezone_get(),
            ],
            'database' => $this->getDatabaseInfo(),
            'cache' => config('cache.default'),
            'queue' => config('queue.connection'),
            'models_count' => [
                'users' => \App\Models\User::count(),
                'guests' => \App\Models\Guest::count(),
                'rooms' => \App\Models\Room::count(),
                'reservations' => \App\Models\Reservation::count(),
                'invoices' => \App\Models\Invoice::count(),
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    private function getDatabaseInfo()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'Connected',
                'driver' => config('database.default'),
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'Failed',
                'error' => $e->getMessage(),
            ];
        }
    }
}
