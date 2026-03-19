<?php

namespace App\Repositories\Contracts;

use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TourRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Tour;
    public function findBySlug(string $slug): ?Tour;
    public function create(array $data): Tour;
    public function update(Tour $tour, array $data): bool;
    public function delete(Tour $tour): bool;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function getActive(): Collection;
    public function getFeatured(): Collection;
    public function search(string $term): Collection;
}
