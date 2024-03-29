<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("/login", name="app_login", methods={"POST"})
     */
    public function login(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['error' => 'Invalid login request: check that the Content-Type header is "application/json".'], Response::HTTP_BAD_REQUEST);
        }
        return $this->json(['user' => $this->getUser() ? $this->getUser()->getId() : null,]);
    }
}
