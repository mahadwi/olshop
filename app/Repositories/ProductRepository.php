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
            ->where('is_active', true)            
            ->when(isset($params['brand_id']), function ($query) use ($params) {
                $query->whereIn('brand_id', $params['brand_id']);
            })
            ->when(isset($params['category_id']), function ($query) use ($params) {
                $query->whereIn('product_category_id', $params['category_id']);
            })
            ->when(isset($params['color_id']), function ($query) use ($params) {
                $query->where('color_id', $params['color_id']);
            })
            ->when(isset($params['price_min']) && isset($params['price_max']), function ($query) use ($params) {               
                $query->where('price', '>=', $params['price_min']);
                $query->where('price', '<=', $params['price_max']);
            })
            ->when(isset($params['sort']), function ($query) use ($params) {            
                $query->orderBy($params['sort']['name'], $params['sort']['ordering']);   
            })
            ->when(isset($params['is_new_arrival']) && $params['is_new_arrival'] == true, function ($query) use ($params) {
                $query->isNewArrival();
            })
            ;

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
