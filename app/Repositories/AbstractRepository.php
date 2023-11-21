<?php

namespace App\Repositories;

abstract class AbstractRepository
{
    protected string $model;

    public function find($id)
    {
        return $this->getModel()->find($id);
    }

    protected function getModel()
    {
        return new $this->model();
    }
}
