<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SomeUsefulController extends AbstractController
{
    #[Route('/some/useful', name: 'app_some_useful')]
    public function index(): Response
    {
        return $this->render('some_useful/index.html.twig', [
            'controller_name' => 'SomeUsefulController',
        ]);
    }
}
