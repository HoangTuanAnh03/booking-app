<?php

namespace App\Services\Impl;

use App\Http\Requests\CourtRequest;
use App\Http\Requests\UpdateCourtStatusRequest;
use App\Http\Requests\CourtSpecialTimeRequest;
use App\Models\Court;
use App\Repository\ICourtRepository;
use App\Services\ICourtService;
use Illuminate\Support\Str;

class CourtService implements ICourtService
{
    private ICourtRepository $repository;

    public function __construct(ICourtRepository $repository) {
        $this->repository = $repository;
    }

    public function show(): array {
        return $this->repository->show();
    }

    public function findById(string $id): ?Court {
        return $this->repository->getById($id);
    }

    public function add(CourtRequest $request): Court {
        $data = [
            'court_id' => Str::uuid(),
            'field_id' => $request->get('field_id'),
            'court_name' => $request->get('court_name'),
            'is_active' => $request->get('is_active'),
        ];
        return $this->repository->store($data);
    }

    public function update(string $id, UpdateCourtStatusRequest $request): ?Court {
        $data["is_active"] = $request->get('is_active');
        return $this->repository->update($data, $id);
    }

    public function delete(string $id): ?Court {
        return $this->repository->delete($id);
    }

    public function createSpecialTimes(CourtSpecialTimeRequest $request): array{

        return $this->repository->createSpecialTimes($request);
    }
}
