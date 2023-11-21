<?php

namespace App\Serializer;

use League\Fractal\Serializer\DataArraySerializer;

class FractalSerializer extends DataArraySerializer
{
    /**
     * {@inheritDoc}
     */
    public function collection(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function item(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function null(): ?array
    {
        return [];
    }
}
