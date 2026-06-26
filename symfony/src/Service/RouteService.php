<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RouteService
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function someMethod(): void
    {
        // Generate a URL with no route arguments
        $url1 = $this->urlGenerator->generate('route10_with_locale');

        // Generate a URL with route arguments
        $url2 = $this->urlGenerator->generate('route10_with_locale', [
            '_locale' => 'en',
        ]);

        // Generated URLs are "absolute paths" by default. Pass a third optional
        // argument to generate different URLs (e.g. an "absolute URL")
        $url3 = $this->urlGenerator->generate('route10_with_locale', [], UrlGeneratorInterface::ABSOLUTE_URL);

        // When a route is localized, Symfony uses by default the current request locale
        // pass a different '_locale' value if you want to set the locale explicitly
        $url4 = $this->urlGenerator->generate('route10_with_locale', ['_locale' => 'fr']);
    }
}