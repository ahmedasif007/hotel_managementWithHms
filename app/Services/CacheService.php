<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Invoice;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour

    /**
     * Get available rooms with caching
     */
    public static function getAvailableRooms($checkInDate, $checkOutDate)
    {
        $cacheKey = "available_rooms_{$checkInDate}_{$checkOutDate}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($checkInDate, $checkOutDate) {
            return Room::whereNotIn('id', function ($query) use ($checkInDate, $checkOutDate) {
                $query->select('room_id')
                    ->from('reservations')
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_date', '<', $checkOutDate)
                            ->where('check_out_date', '>', $checkInDate);
                    });
            })
                ->where('status', 'available')
                ->with('type', 'images')
                ->get();
        });
    }

    /**
     * Get room statistics with caching
     */
    public static function getRoomStatistics()
    {
        return Cache::remember('room_statistics', self::CACHE_TTL, function () {
            return [
                'total' => Room::count(),
                'available' => Room::where('status', 'available')->count(),
                'occupied' => Room::where('status', 'occupied')->count(),
                'maintenance' => Room::where('status', 'maintenance')->count(),
            ];
        });
    }

    /**
     * Get revenue statistics with caching
     */
    public static function getRevenueStatistics($month = null)
    {
        $month = $month ?? now()->month;
        $cacheKey = "revenue_stats_{$month}";

        return Cache::remember($cacheKey, self::CACHE_TTL * 24, function () use ($month) {
            return Invoice::whereMonth('issue_date', $month)
                ->selectRaw('SUM(total) as total_revenue, COUNT(*) as invoice_count, AVG(total) as average_invoice')
                ->first();
        });
    }

    /**
     * Get guest statistics with caching
     */
    public static function getGuestStatistics()
    {
        return Cache::remember('guest_statistics', self::CACHE_TTL, function () {
            return [
                'total' => \App\Models\Guest::count(),
                'current_reservations' => Reservation::where('status', 'checked_in')->count(),
                'total_spent' => Invoice::sum('total'),
            ];
        });
    }

    /**
     * Invalidate room-related caches
     */
    public static function invalidateRoomCache()
    {
        Cache::forget('room_statistics');
        Cache::flush(); // Clear all related caches
    }

    /**
     * Invalidate reservation caches
     */
    public static function invalidateReservationCache()
    {
        // Clear all caches with available_rooms pattern
        Cache::forget('available_rooms');
    }

    /**
     * Invalidate revenue caches
     */
    public static function invalidateRevenueCache()
    {
        for ($i = 1; $i <= 12; $i++) {
            Cache::forget("revenue_stats_{$i}");
        }
    }
}
