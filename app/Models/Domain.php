<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'domain',
        'subdomain',
        'is_primary',
        'is_https',
        'status',
        'ssl_config',
        'verified_at',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_https' => 'boolean',
        'ssl_config' => 'array',
        'verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($domain) {
            // Only one primary domain per tenant
            if ($domain->is_primary) {
                static::where('tenant_id', $domain->tenant_id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });

        static::updating(function ($domain) {
            // Only one primary domain per tenant
            if ($domain->is_primary && $domain->isDirty('is_primary')) {
                static::where('tenant_id', $domain->tenant_id)
                    ->where('id', '!=', $domain->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFullDomain(): string
    {
        if ($this->subdomain) {
            return $this->subdomain . '.' . $this->domain;
        }
        return $this->domain;
    }

    public function getFullUrl(): string
    {
        $protocol = $this->is_https ? 'https' : 'http';
        return $protocol . '://' . $this->getFullDomain();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function markAsVerified(): void
    {
        $this->update([
            'verified_at' => now(),
            'status' => 'active'
        ]);
    }

    public static function findByDomain(string $domain): ?self
    {
        // Try to find exact domain match first
        $found = static::where('domain', $domain)
            ->whereNull('subdomain')
            ->where('status', 'active')
            ->first();

        if ($found) {
            return $found;
        }

        // Try to find subdomain match
        $parts = explode('.', $domain);
        if (count($parts) >= 2) {
            $subdomain = $parts[0];
            $mainDomain = implode('.', array_slice($parts, 1));
            
            return static::where('domain', $mainDomain)
                ->where('subdomain', $subdomain)
                ->where('status', 'active')
                ->first();
        }

        return null;
    }
}
