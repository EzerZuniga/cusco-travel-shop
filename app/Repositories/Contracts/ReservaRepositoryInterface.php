<?php

namespace App\Repositories\Contracts;

use App\Models\Reserva;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ReservaRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Reserva;
    public function create(array $data): Reserva;
    public function update(Reserva $reserva, array $data): bool;
    public function delete(Reserva $reserva): bool;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function getByUser(int $userId): Collection;
    public function getByTour(int $tourId): Collection;
    public function getByEstado(string $estado): Collection;
}
