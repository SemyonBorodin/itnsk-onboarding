<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestBlogController extends AbstractController
{
    #[Route('/test-blog{}', name: 'blog_list', priority: 2)]
    public function list(): ?Response
    {
        $blog_list = ['blog1', 'blog2'];

        return new Response(
            '<html lang="en"><body>blog_list: ' . implode(', ', $blog_list) . '</body></html>'
        );
    }

    #[Route('/test-blog/{!item<\d+>}', name: 'blog_item',
//        requirements: ['item' => '[0-9]+'],
        methods: ['GET'],
        locale: 'en')]
    public function item(int $item = 0): Response
    {
        $blog_list = ['blog1', 'blog2'];

        if (!isset($blog_list[$item])) {
//            return new Response('Not found', 404);
//            $url = $this->generateUrl('blog_item', ['item' => 0]);
//            return new RedirectResponse($url);
            return $this->redirectToRoute('blog_item', ['item' => 0], Response::HTTP_MOVED_PERMANENTLY); // redirect из symfony helper-a
        }

        return new Response(
            '<html lang="en"><body>blog: ' . $blog_list[$item] . '</body></html>'
        );
    }
}
