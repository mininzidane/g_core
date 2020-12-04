<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Controller\Api\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="api_get_token", methods={"POST"})
     */
    public function login(
        Request $request,
        UserPasswordEncoderInterface $userPasswordEncoder,
        UserRepository $userRepository
    ): JsonResponse
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $user = $userRepository->findOneBy(['login' => $username]);

        if ($user === null) {
            return $this->errorResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$userPasswordEncoder->isPasswordValid($user, $password)) {
            return $this->errorResponse(['error' => 'Password incorrect'], Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(['token' => $user->getApiKey()]);
    }
}
