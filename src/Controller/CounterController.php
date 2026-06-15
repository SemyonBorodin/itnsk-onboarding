<?php

namespace App\Controller;

use App\EventSubscriber\VisitCounterSubscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class CounterController extends AbstractController
{
    public function show(RequestStack $requestStack): Response
    {
        $request = $requestStack->getMainRequest();

        return $this->render('counter/show.html.twig', [
            'session_counter' => (int) $request
                ?->getSession()
                ->get(VisitCounterSubscriber::SESSION_COUNTER, 0),
            'cookie_counter' => (int) $request
                ?->attributes
                ->get(
                    VisitCounterSubscriber::CURRENT_COOKIE_COUNTER,
                    $request->cookies->getInt(
                        VisitCounterSubscriber::COOKIE_COUNTER, 0)
                ),
        ]);
    }
}
