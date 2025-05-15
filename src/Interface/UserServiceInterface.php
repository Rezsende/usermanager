<?php

namespace App\Interface;

use App\Entity\User;

interface UserServiceInterface
{
    /**
     * @return User[]
     */
    public function listarTodos(): array;
}
