<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'app_category_show')]
    public function show(
        Category $category,
        PublicationRepository $publicationRepository,
    ): Response {
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'publications' => $publicationRepository->findBy(
                ['category' => $category],
                ['createdAt' => 'DESC'],
            ),
            'sidebar_publications' => $publicationRepository->findBy(
                [],
                ['createdAt' => 'DESC'],
                5,
            ),
        ]);
    }
}
