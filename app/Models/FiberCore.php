<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiberCore extends Model
{
    use HasFactory;

    protected $fillable = [
        'cable_id', // tambahkan ini
        'nama_site',
        'region',
        'tube_number',
        'core',
        'warna',
        'status',
        'penggunaan',
        'otdr',
        'source_site',
        'destination_site',
        'keterangan',
        'tube'
    ];

    protected $casts = [
        'tube_number' => 'integer',
        'core' => 'integer',
        'otdr' => 'integer',
    ];

    /**
     * Boot method to auto-generate tube field
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->tube_number) {
                $model->tube = "TUBE {$model->tube_number}";
            }
        });
    }

    /**
     * Scope for active cores
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for inactive cores
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    /**
     * Scope for problem cores
     */
    public function scopeProblems($query)
    {
        return $query->where('penggunaan', 'NOK');
    }

    /**
     * Scope for region filter
     */
    public function scopeByRegion($query, $region)
    {
        if ($region && $region !== 'All') {
            return $query->where('region', $region);
        }
        return $query;
    }

    /**
     * Scope for status filter
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'All') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_site', 'like', "%{$search}%")
                    ->orWhere('region', 'like', "%{$search}%")
                    ->orWhere('source_site', 'like', "%{$search}%")
                    ->orWhere('destination_site', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('tube', 'like', "%{$search}%")
                    ->orWhere('core', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'Active' => 'bg-green-100 text-green-800',
            'Inactive' => 'bg-gray-100 text-gray-800'
        ];
        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get penggunaan badge class
     */
    public function getPenggunaanBadgeAttribute()
    {
        $colors = [
            'OK' => 'bg-blue-100 text-blue-800',
            'NOK' => 'bg-red-100 text-red-800',
            'Idle' => 'bg-yellow-100 text-yellow-800'
        ];
        return $colors[$this->penggunaan] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get region badge class
     */
    public function getRegionBadgeAttribute()
    {
        $colors = [
            'Denpasar Utara' => 'bg-purple-100 text-purple-800',
            'Denpasar Selatan' => 'bg-purple-100 text-purple-800',
            'Badung' => 'bg-orange-100 text-orange-800',
            'Gianyar' => 'bg-teal-100 text-teal-800',
            'Tabanan' => 'bg-indigo-100 text-indigo-800',
            'Klungkung' => 'bg-pink-100 text-pink-800',
            'Buleleng' => 'bg-cyan-100 text-cyan-800',
        ];
        return $colors[$this->region] ?? 'bg-gray-100 text-gray-800';
    }
}
