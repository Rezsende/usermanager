<?php

namespace App\Controller;


use App\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
            'message' => 'Welcome to Pigz Side!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

   #[Route('/users', name: 'list_users', methods: ['GET'])]
    public function listar(): JsonResponse
    {
        $users = $this->userService->listarTodos();

        $data = array_map(fn($user) => [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], $users);

      return $this->json($data, 200, [], ['json_encode_options' => JSON_UNESCAPED_UNICODE]);
    }

  

    #[Route('/users', name: 'criar_user', methods: ['POST'])]
    public function criar(Request $request): JsonResponse
    {
        $dados = json_decode($request->getContent(), true);

        if (!isset($dados['name'], $dados['email'], $dados['password'])) {
            return $this->json(['error' => 'Campos obrigatórios: name, email, password'], 400);
        }

        $user = $this->userService->criar($dados);

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], 201);
    }

}
