<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait HasTenant
{
    protected static function bootHasTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (empty($model->user_id) && Auth::hasUser()) {
                $user = Auth::user();
                if ($user->hasRole('admin-outlet')) {
                    $model->user_id = $user->id;
                } else if ($user->outlet_id) {
                    static $tenantId = null;
                    if ($tenantId === null) {
                        $outlet = \App\Models\Outlet::withoutGlobalScope(TenantScope::class)->find($user->outlet_id);
                        $tenantId = $outlet ? $outlet->user_id : null;
                    }
                    if ($tenantId) {
                        $model->user_id = $tenantId;
                    }
                }
            }
        });
    }
}
