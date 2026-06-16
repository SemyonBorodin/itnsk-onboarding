<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PublicationImageUploadController extends AbstractController
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
    ];

    #[Route('/publication/image/upload', name: 'app_publication_image_upload', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(
        Request $request,
        #[Autowire('%kernel.project_dir%/public/uploads/publications')]
        string $uploadDirectory,
    ): JsonResponse {
        $file = $request->files->get('upload');

        if ($file === null) {
            return $this->jsonUploadError('Файл не передан.', 400);
        }

        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES, true)) {
            return $this->jsonUploadError('Можно загружать только изображения.', 415);
        }

        if ($file->getSize() !== null && $file->getSize() > 5 * 1024 * 1024) {
            return $this->jsonUploadError('Изображение должно быть не больше 5 МБ.', 413);
        }

        $extension = $file->guessExtension() ?: 'bin';
        $filename = bin2hex(random_bytes(16)).'.'.$extension;

        try {
            $file->move($uploadDirectory, $filename);
        } catch (FileException) {
            return $this->jsonUploadError('Не удалось сохранить изображение.', 500);
        }

        return $this->json([
            'url' => '/uploads/publications/'.$filename,
        ]);
    }

    private function jsonUploadError(string $message, int $status): JsonResponse
    {
        return $this->json([
            'error' => [
                'message' => $message,
            ],
        ], $status);
    }
}
