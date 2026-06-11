<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\User;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PublicationController extends AbstractController
{
    #[Route('/publication', name: 'app_publication')]
    public function index(): Response
    {
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'PublicationController',
        ]);
    }

    #[Route('/blog/{id}', name: 'app_publication_show', requirements: ['id' => '\d+'])]
    public function show(
        Publication $publication,
        PublicationRepository $publicationRepository
    ): Response {
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
            'sidebar_publications' => $publicationRepository->findBy(
                [],
                ['createdAt' => 'DESC'],
                5
            ),
        ]);
    }

    #[Route('/publication/create', name: 'app_publication_create')]
    #[Route('/publication/{id}/update', name: 'app_publication_update', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function form(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapEntity(id: 'id')]
        ?Publication $publication = null
    ): Response {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException(
                'Создавать публикации может только пользователь из базы данных.'
            );
        }

        $isNew = $publication === null;

        if ($isNew) {
            $publication = (new Publication())->setAuthor($user);
        } elseif (
            $publication->getAuthor()?->getId() !== $user->getId()
            && !$this->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException(
                'Редактировать публикацию может только её автор.'
            );
        }

        $title = $isNew
            ? 'Новая публикация'
            : 'Редактирование публикации';

        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publication);
            $entityManager->flush();

            $this->addFlash('success', 'Публикация сохранена!');

            return $this->redirectToRoute('app_home');
        }

        if ($form->isSubmitted()) {
            $this->addFlash('warning', 'Проверьте введённые данные.');
        }

        return $this->render('publication/form.html.twig', [
            'form' => $form,
            'title' => $title,
        ]);
    }
}
