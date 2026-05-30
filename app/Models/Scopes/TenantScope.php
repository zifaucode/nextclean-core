<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::hasUser()) {
            $user = Auth::user();
            
            // Super Admin can see everything
            if ($user->hasRole('super-admin')) {
                return;
            }

            // Admin Outlet sees only their own data
            if ($user->hasRole('admin-outlet')) {
                $builder->where($model->getTable() . '.user_id', $user->id);
                return;
            }

            // Other roles (Kasir, Supervisor, Karyawan) are scoped by their outlet's owner (Admin)
            if ($user->outlet_id) {
                // To avoid multiple queries for every model, we could cache the admin_id on the user session,
                // but for now, we'll fetch the outlet's user_id if not cached.
                // Assuming we can eager load or use a simple query
                static $tenantId = null;
                if ($tenantId === null) {
                    $outlet = \App\Models\Outlet::find($user->outlet_id);
                    $tenantId = $outlet ? $outlet->user_id : null;
                }

                if ($tenantId) {
                    $builder->where($model->getTable() . '.user_id', $tenantId);
                } else {
                    // Fallback to ensure they see nothing if they have an invalid outlet
                    $builder->where($model->getTable() . '.user_id', -1);
                }
            } else {
                // If the user is not superadmin/admin and has no outlet, they shouldn't see anything
                $builder->where($model->getTable() . '.user_id', -1);
            }
        }
    }
}
