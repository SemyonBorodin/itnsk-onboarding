<?php

namespace App\Controller;

use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        PublicationRepository $publicationRepository
    ): Response {
        $publications = $publicationRepository->findBy(
            [],
            ['createdAt' => 'DESC']
        );

        $session = $request->getSession();
        $sessionVisits = (int) $session->get('session_visits', 0) + 1;
        $session->set('session_visits', $sessionVisits);

        $totalVisits = $request->cookies->getInt('total_visits', 0) + 1;

        $response = $this->render('main/index.html.twig', [
            'publications' => $publications,
            'session_visits' => $sessionVisits,
            'total_visits' => $totalVisits,
        ]);

        $response->headers->setCookie(
            Cookie::create('total_visits')
                ->withValue((string) $totalVisits)
                ->withExpires(new \DateTimeImmutable('+30 days'))
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withSameSite(Cookie::SAMESITE_LAX)
        );

        return $response;
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig');
    }
}
