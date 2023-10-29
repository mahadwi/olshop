<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository extends AbstractRepository
{
    protected string $model = Product::class;

    public function get($params = [])
    {
        $query = $this->getModel();

        $query = $query->orderBy('name');

        $query = isset($params['pagination'])
            ? $query->paginate($params['pagination'])
            : $query->get();

        return $query;
    }

    public function search($query, $params = [])
    {
        $queryBuilder = $this->getModel()->newQuery();

        $queryBuilder->where(function ($queryBuilder) use ($query) {
            $columns = ['name'];

            foreach ($columns as $column) {
                $queryBuilder->orWhere($column, 'ilike', "%{$query}%");
            }
        });

        $queryBuilder->orderBy('name');
            
        return isset($params['pagination'])
            ? $queryBuilder->paginate($params['pagination'])
            : $queryBuilder->get();
    }

    public function getProductApi(array $params = [])
    {
        $query = $this->getModel()        
            ->when(isset($params['search']), function ($query) use ($params) {
                $query->where('name', 'ilike', "%{$params['search']}%");
            })
            ->where('is_active', true);
            ;
            // ->whereHas('price', function ($query) use ($params) {
            //     $query->where([
            //         ['price', '!=', 0],
            //         ['city_id', $params['city_id']],
            //         ['service_type', $params['service_type']]
            //     ])->when($params['service_type'] == ServiceType::DRIVER, function ($query) {
            //         $query->where([
            //             ['cut_off', CutOffType::ENDOFDAY],
            //             ['is_all_in', true]
            //         ]);
            //     })->when($params['service_type'] == ServiceType::DRIVERLESS, function ($query) use ($params) {
            //         $query->where([
            //             ['cut_off', $params['city']->cut_off],
            //             ['transmission_type', $params['transmission']]
            //         ]);
            //     });
            // })
            // ->when(isset($params['car_type_id']), function ($query) use ($params) {
            //     $query->where('type_of_car_id', $params['car_type_id']);
            // })
            // ->when(isset($params['passenger']), function ($query) use ($params) {
            //     $query->where('passenger', $params['passenger']);
            // })
            // ->when(isset($params['search']), function ($query) use ($params) {
            //     $query->where('name', 'ilike', "%{$params['search']}%");
            // })
            // ->whereHas('carDetails');


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
