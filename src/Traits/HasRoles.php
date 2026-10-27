<?php

namespace Acsi\AuthCode\Traits;

trait HasRoles
{
    public function isAdmin(): bool
    {
        return ($this->role ?? null) === config('auth-code.admin_role', 'admin');
    }

    public function isClient(): bool
    {
        return ($this->role ?? null) === config('auth-code.client_role', 'client');
    }

    public function isActive(): bool
    {
        return (bool) ($this->is_active ?? true);
    }
}