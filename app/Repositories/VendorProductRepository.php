<?php

namespace App\Repositories;

use App\Models\VendorProduct;
use Illuminate\Support\Collection;

class VendorProductRepository extends AbstractRepository
{
    protected string $model = VendorProduct::class;

    public function getProductApi(array $params = [])
    {
        
        $vendor = auth()->user()->vendor->id;

        $query = $this->getModel()        
            ->when(isset($params['search']), function ($query) use ($params) {
                $query->where('name', 'ilike', "%{$params['search']}%");
            })
            ->where('vendor_id', $vendor)
            ;

        if (isset($params['page'])) {
            if (isset($params['itemPerpage'])) {
                $query = $query->paginate($params['itemPerpage']);
            } else {
                $query = $query->paginate(10);
            }
        } else {
            $query = $query->get();
        }

        return $query;
    }

}
