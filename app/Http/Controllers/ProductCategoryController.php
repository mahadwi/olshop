<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\ProductCategoryIndexRequest;
use App\Http\Requests\ProductCategoryUpdateRequest;

class ProductCategoryController extends Controller
{
    public function index(ProductCategoryIndexRequest $request)
    {
        $productCategories = ProductCategory::query();
        if ($request->has('search')) {
            $productCategories->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $productCategories->orderBy($request->field, $request->order);
        }
        $perPage = $request->has('perPage') ? $request->perPage : 10;
        
        return Inertia::render('ProductCategory/Index', [
            'title'             => 'Data '.__('app.label.product_category'),
            'filters'           => $request->all(['search', 'field', 'order']),
            'perPage'           => (int) $perPage,
            'productCategories' => $productCategories->paginate($perPage),
            // 'breadcrumbs'   => [['label' => __('app.label.user'), 'href' => route('user.index')]],
        ]);
    }  

    public function store(ProductCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $productCategory = ProductCategory::create([
                'name' => $request->name,
            ]);
            DB::commit();
            return back()->with('success', __('app.label.created_successfully', ['name' => $productCategory->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.product_category')]) . $th->getMessage());
        }
    }

    public function update(ProductCategoryUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->update([
                'name'      => $request->name,
                'is_active' => $request->is_active
            ]);

            DB::commit();
            return back()->with('success', __('app.label.updated_successfully', ['name' => $productCategory->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $productCategory->name]) . $th->getMessage());
        }
    }

    public function destroy(ProductCategory $productCategory)
    {
        try {
            $productCategory->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $productCategory->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $productCategory->name]) . $th->getMessage());
        }
    }
}
