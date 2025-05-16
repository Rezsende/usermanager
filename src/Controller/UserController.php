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


    #[Route('/user/{id}', name: 'buscar_usuario', methods: ['GET'])]
    public function buscarPorId(int $id): JsonResponse
    {
        $user = $this->userService->buscarPorId($id);

        if (!$user) {
            return $this->json([
                'message' => 'Usuário não encontrado.',
                'status' => 404,
            ], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ];

        return $this->json($data, 200, [], ['json_encode_options' => JSON_UNESCAPED_UNICODE]);
    }
  

  #[Route('/users', name: 'criar_user', methods: ['POST'])]
public function criar(Request $request): JsonResponse
{
    try {
        $dados = json_decode($request->getContent(), true);

        if (empty($dados['name']) || empty($dados['email']) || empty($dados['password'])) {
            return $this->json(['error' => 'Todos os campos são obrigatórios'], 400);
        }

        $user = $this->userService->criar($dados);

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], 201);

    } catch (\RuntimeException $e) {
        return $this->json(['error' => $e->getMessage()], $e->getCode());
    } catch (\Exception $e) {
        return $this->json(['error' => 'Erro interno no servidor'], 500);
    }
}

}
