<?php

namespace App\Repositories;

use App\Models\Tour;
use App\Repositories\Contracts\TourRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TourRepository implements TourRepositoryInterface
{
    public function all(): Collection
    {
        return Tour::all();
    }

    public function find(int $id): ?Tour
    {
        return Tour::find($id);
    }

    public function findBySlug(string $slug): ?Tour
    {
        return Tour::where('slug', $slug)->first();
    }

    public function create(array $data): Tour
    {
        return Tour::create($data);
    }

    public function update(Tour $tour, array $data): bool
    {
        return $tour->update($data);
    }

    public function delete(Tour $tour): bool
    {
        return $tour->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Tour::paginate($perPage);
    }

    public function getActive(): Collection
    {
        return Tour::where('activo', true)->get();
    }

    public function getFeatured(): Collection
    {
        return Tour::featured()->get();
    }

    public function search(string $term): Collection
    {
        return Tour::search($term)->get();
    }
}
