<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersIndex;
use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersRegionsIndex;
use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersRegionsShow;
use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersRegionsSizesIndex;
use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersRegionsSizesShow;
use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersShow;
use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersSizesIndex;
use ArtisanBuild\ForgeClient\Requests\Providers\ProvidersSizesShow;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Providers extends Resource
{
    /**
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function providersIndex(?int $pagesize, ?string $pagecursor): Response
    {
        return $this->connector->send(new ProvidersIndex($pagesize, $pagecursor));
    }

    /**
     * @param  int  $provider  The provider ID
     */
    public function providersShow(int $provider): Response
    {
        return $this->connector->send(new ProvidersShow($provider));
    }

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function providersSizesIndex(int $provider, ?int $pagesize, ?string $pagecursor): Response
    {
        return $this->connector->send(new ProvidersSizesIndex($provider, $pagesize, $pagecursor));
    }

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerSize  The provider size ID
     */
    public function providersSizesShow(int $provider, int $providerSize): Response
    {
        return $this->connector->send(new ProvidersSizesShow($provider, $providerSize));
    }

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function providersRegionsIndex(int $provider, ?int $pagesize, ?string $pagecursor): Response
    {
        return $this->connector->send(new ProvidersRegionsIndex($provider, $pagesize, $pagecursor));
    }

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerRegion  The provider region ID
     */
    public function providersRegionsShow(int $provider, int $providerRegion): Response
    {
        return $this->connector->send(new ProvidersRegionsShow($provider, $providerRegion));
    }

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerRegion  The provider region ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function providersRegionsSizesIndex(
        int $provider,
        int $providerRegion,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new ProvidersRegionsSizesIndex($provider, $providerRegion, $pagesize, $pagecursor));
    }

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerRegion  The provider region ID
     * @param  int  $providerSize  The provider size ID
     */
    public function providersRegionsSizesShow(int $provider, int $providerRegion, int $providerSize): Response
    {
        return $this->connector->send(new ProvidersRegionsSizesShow($provider, $providerRegion, $providerSize));
    }
}
