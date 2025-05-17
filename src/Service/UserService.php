<?php
namespace App\Service;


use App\Entity\User; 
use App\Interface\UserServiceInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;




class UserService implements UserServiceInterface
{
   private UserRepository $userRepository;
   private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function listarTodos(): array
    {
       try{ 
        return $this->userRepository->findAll();
    }
       catch(\Exception $e){
        return [];
       }
    }

    
    public function criar(array $dados): User
    {
        
        if (empty($dados['name']) || empty($dados['email']) || empty($dados['password'])) {
            throw new \RuntimeException('Todos os campos são obrigatórios', 400);
        }

    
        if ($this->userRepository->findOneBy(['email' => $dados['email']])) {
            throw new \RuntimeException('E-mail já cadastrado', 409);
        }

        
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('E-mail inválido', 400);
        }

        $user = new User();
        $user->setName($dados['name']);
        $user->setEmail($dados['email']);
        $user->setPassword(password_hash($dados['password'], PASSWORD_BCRYPT));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }


    public function buscarPorId(int $id): ?User
    {
        try {
             return $this->userRepository->find($id);
        }catch (\Exception $e) {
            return $e;
        }
    }


    public function atualizarPorId(int $id, array $dados): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \RuntimeException('Usuário não encontrado', 404);
        }

        if (empty($dados['name']) || empty($dados['email']) || empty($dados['password'])) {
            throw new \RuntimeException('Todos os campos são obrigatórios', 400);
        }

     
       

        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('E-mail inválido', 400);
        }

        $user->setName($dados['name']);
        $user->setEmail($dados['email']);
        $user->setPassword(password_hash($dados['password'], PASSWORD_BCRYPT));

        $this->entityManager->flush();

        return $user;
    }


   
}
