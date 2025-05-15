<?php

namespace App\Controller;


use App\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
     private UserServiceInterface $userService;

   public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }


    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

  #[Route('/users', name: 'list_users', methods: ['GET'])]
    public function listar(): JsonResponse
    {
        $user = $this->userService->listarTodos();
        return $this->json($user);
    }
}
