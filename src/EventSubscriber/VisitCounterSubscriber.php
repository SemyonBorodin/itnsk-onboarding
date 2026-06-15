<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class VisitCounterSubscriber implements EventSubscriberInterface // класс, который подписывается на события Symfony
{
    public const SESSION_COUNTER = 'session_counter';
    public const COOKIE_COUNTER = 'cookie_counter';
    public const CURRENT_COOKIE_COUNTER = '_current_cookie_counter';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        if (
            !$request->isMethod('GET')
            || !is_string($route)
            || str_starts_with($route, '_')
        ) {
            return;
        }

        $session = $request->getSession();

        $sessionCounter = (int) $session->get(self::SESSION_COUNTER, 0) + 1;
        $session->set(self::SESSION_COUNTER, $sessionCounter);

        $cookieCounter = $request->cookies->getInt(self::COOKIE_COUNTER, 0) + 1;
        $request->attributes->set(self::CURRENT_COOKIE_COUNTER, $cookieCounter);
        // временно сохраняем новое значение внутри запроса --
        // это позволяет CounterController показать его до отправки cookie браузеру
    }

    // запись cookie в ответ
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $cookieCounter = $event->getRequest()->attributes->get(
            self::CURRENT_COOKIE_COUNTER
        );

        if (!is_int($cookieCounter)) {
            return;
        }

        $event->getResponse()->headers->setCookie(
            Cookie::create(self::COOKIE_COUNTER)
                ->withValue((string) $cookieCounter)
                ->withExpires(new \DateTimeImmutable('+30 days'))
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withSameSite(Cookie::SAMESITE_LAX)
        );
    }
}
