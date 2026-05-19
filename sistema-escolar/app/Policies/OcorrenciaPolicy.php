<?php

namespace App\Policies;

use App\Models\Ocorrencia;
use App\Models\User;

class OcorrenciaPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos podem listar (com filtros)
    }

    public function view(User $user, Ocorrencia $ocorrencia): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAqv();
    }

    public function update(User $user, Ocorrencia $ocorrencia): bool
    {
        return $user->isAqv();
    }

    public function delete(User $user, Ocorrencia $ocorrencia): bool
    {
        return $user->isAqv();
    }

    public function aprovar(User $user, Ocorrencia $ocorrencia): bool
    {
        return $user->isAqv() && $ocorrencia->isPendente();
    }

    public function confirmarPortaria(User $user, Ocorrencia $ocorrencia): bool
    {
        return $user->isPortaria() && $ocorrencia->isAprovado();
    }
}