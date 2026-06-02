<?php

namespace App\service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SomeService
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function someMethod(): void
    {
        $user = null;

        // generate a URL with no route arguments
        $signUpPage = $this->urlGenerator->generate('sign_up');

        // generate a URL with route arguments
        $userProfilePage = $this->urlGenerator->generate('user_profile', [
            'username' => $user->getUserIdentifier(),
        ]);

        // generated URLs are "absolute paths" by default. Pass a third optional
        // argument to generate different URLs (e.g. an "absolute URL")
        $signUpPage = $this->urlGenerator->generate('sign_up', [], UrlGeneratorInterface::ABSOLUTE_URL);

        // when a route is localized, Symfony uses by default the current request locale
        // pass a different '_locale' value if you want to set the locale explicitly
        $signUpPageInDutch = $this->urlGenerator->generate('sign_up', ['_locale' => 'nl']);
    }
}
?>
