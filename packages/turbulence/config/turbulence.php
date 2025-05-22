<?php

return [
    'installed' => false,
    'user_model' => '\ArtisanBuild\Turbulence\Models\UserModel',
    'account_model' => '\ArtisanBuild\Turbulence\Models\Account',
    'account_session_urls' => [
        'enabled' => false,
        'index_key' => 'u', // https://example.com/u/0/dashboard - loads first account from session('accounts')
        'id_key' => 'i', // https://example.com/i/301319658141229056/dashboard - Loads account by id (impersonation only)
    ],
];
