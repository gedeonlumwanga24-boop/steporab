<?php

namespace App\Repositories\Contracts;

use App\DTOs\OrderData;
use App\Models\Commande;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Commande;

    public function findForUser(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function create(OrderData $data): Commande;

    public function updateStatus(Commande $order, string $statut): Commande;

    public function cancel(Commande $order): bool;
}
