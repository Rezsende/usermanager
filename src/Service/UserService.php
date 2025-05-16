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
        return $this->userRepository->findAll();
    }

    

    public function criar(array $dados): User
    {
        $user = new User();
        $user->setName($dados['name'] ?? null);
        $user->setEmail($dados['email'] ?? null);
        $user->setPassword(password_hash($dados['password'], PASSWORD_BCRYPT));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }


    // public function buscarPorId(int $id): ?Pessoa
    // {
    //     return $this->pessoaRepository->find($id);
    // }

    // public function criar(Pessoa $pessoa): Pessoa
    // {
    //     $this->entityManager->persist($pessoa);
    //     $this->entityManager->flush();

    //     return $pessoa;
    // }

    // public function atualizar(Pessoa $pessoa): Pessoa
    // {
    //     $this->entityManager->flush();

    //     return $pessoa;
    // }

    // public function remover(Pessoa $pessoa): void
    // {
    //     $this->entityManager->remove($pessoa);
    //     $this->entityManager->flush();
    // }
}
