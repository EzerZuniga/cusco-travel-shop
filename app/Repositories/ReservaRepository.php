<?php

namespace App\Repositories;

use App\Models\Reserva;
use App\Repositories\Contracts\ReservaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ReservaRepository implements ReservaRepositoryInterface
{
    public function all(): Collection
    {
        return Reserva::with(['usuario', 'tour'])->get();
    }

    public function find(int $id): ?Reserva
    {
        return Reserva::with(['usuario', 'tour'])->find($id);
    }

    public function create(array $data): Reserva
    {
        return Reserva::create($data);
    }

    public function update(Reserva $reserva, array $data): bool
    {
        return $reserva->update($data);
    }

    public function delete(Reserva $reserva): bool
    {
        return $reserva->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Reserva::with(['usuario', 'tour'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByUser(int $userId): Collection
    {
        return Reserva::where('usuario_id', $userId)
            ->with('tour')
            ->get();
    }

    public function getByTour(int $tourId): Collection
    {
        return Reserva::where('tour_id', $tourId)
            ->with('usuario')
            ->get();
    }

    public function getByEstado(string $estado): Collection
    {
        return Reserva::where('estado', $estado)
            ->with(['usuario', 'tour'])
            ->get();
    }
}
