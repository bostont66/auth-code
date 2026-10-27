<?php

return [
    'code_expiry_minutes' => 10,
    'registration_expiry_hours' => 24,
    'rate_limit_codes_per_window' => 3,
    'rate_limit_window_minutes' => 10,
    'first_user_is_admin' => true,
    'roles' => ['admin', 'client'],
    'admin_role' => 'admin',
    'client_role' => 'client',
];