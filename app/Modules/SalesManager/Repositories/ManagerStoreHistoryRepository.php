<?php


namespace App\Modules\SalesManager\Repositories;

use App\Modules\SalesManager\Models\ManagerStoreHistroy;


class ManagerStoreHistoryRepository
{
    public function store($validData)
    {
        return ManagerStoreHistroy::create($validData)->fresh();
    }
}

