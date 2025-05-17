<?php

namespace App\Interface;

use App\Entity\User;

interface UserServiceInterface
{
    /**
     * @return User[]
     */
    public function listarTodos(): array;
    public function criar(array $dados): User;
    public function buscarPorId(int $id): ?User;
   public function atualizarPorId(int $id, array $dados): ?User;


}
