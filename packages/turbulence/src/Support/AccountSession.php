<?php

namespace ArtisanBuild\Turbulence\Support;

class AccountSession
{
    public function __construct(
        public int $id,
    ) {
        //
    }

    public static function load(int $id): ?AccountSession
    {
        // First let's grab the currently loaded session if it exists
        $account = session('account', ['id' => null]);

        if ($account['id'] === $id) {
            return new self(...$account);
        }

        return null;

    }

    public static function loadFromIndex(int $index): ?AccountSession
    {
        $accounts = session('accounts', []);

        if (! isset($accounts[$index])) {
            return null;
        }

        return new self(...$accounts[$index]);
    }
}
