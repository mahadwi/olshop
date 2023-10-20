<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Constants\Role;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
    {
        $products = Product::query();
        if ($request->has('search')) {
            $products->where('name', 'ILIKE', "%" . $request->search . "%");
            $products->orWhere('brand', 'ILIKE', "%" . $request->search . "%");
            // $products->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $products->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $categories = ProductCategory::active()->get();
        $vendors = User::role(Role::VENDOR)->active()->get();
        
        return Inertia::render('Product/Index', [
            'title'         => 'Data '.__('app.label.product'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'products'      => $products->paginate($perPage),
            'categories'    => $categories,
            'vendors'       => $vendors,
            // 'breadcrumbs'   => [['label' => __('app.label.user'), 'href' => route('user.index')]],
        ]);
    }

    public function create()
    {   
        $categories = ProductCategory::active()->get();
        $vendors = User::role(Role::VENDOR)->active()->get();
        
        return Inertia::render('Product/Create', [
            'title'         => 'Create '.__('app.label.product'),
            'categories'    => $categories,
            'vendors'       => $vendors,
            // 'breadcrumbs'   => [['label' => __('app.label.user'), 'href' => route('user.index')]],
        ]);
    }

    public function store(ProductStoreRequest $request)
    {
        dd($request->all());
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
            ]);
            $user->assignRole($request->role);
            return back()->with('success', __('app.label.created_successfully', ['name' => $user->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.user')]) . $th->getMessage());
        }
    }
}
