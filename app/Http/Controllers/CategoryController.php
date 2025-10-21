<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * GET /catalog/categories
     * Query: keyword, sort_by (name|created_at), sort_dir (asc|desc), per_page (1..50)
     */
    public function index(Request $request)
    {
        $allowedSort = ['name', 'created_at'];
        $sortBy = in_array($request->get('sort_by'), $allowedSort) ? $request->get('sort_by') : 'created_at';
        $sortDir = strtolower($request->get('sort_dir')) === 'asc' ? 'asc' : 'desc';
        $perPage = min(max((int) $request->get('per_page', 10), 1), 50);

        $query = Category::query();

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->where('name', 'like', "%{$kw}%")
                  ->orWhere('slug', 'like', "%{$kw}%");
            });
        }

        $query->orderBy($sortBy, $sortDir);

        $paginator = $query->paginate($perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    /**
     * GET /catalog/categories/{id}
     */
    public function show(int $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        return response()->json(['data' => $category]);
    }

    /**
     * POST /admin/categories
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'message' => 'Category created successfully.',
                'data'    => $category,
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error while creating category.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT/PATCH /admin/categories/{id}
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        try {
            $category->update($request->validated());

            return response()->json([
                'message' => 'Category updated successfully.',
                'data'    => $category,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error while updating category.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /admin/categories/{id}
     * 409 jika masih direferensikan oleh product_categories.
     */
    public function destroy(int $id)
    {
        $category = Category::withCount('products')->find($id);

        if (! $category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        if ($category->products_count > 0) {
            return response()->json([
                'message' => 'Cannot delete category that is used by products.',
            ], 409);
        }

        try {
            $category->delete();
            return response()->noContent(); // 204 tanpa body
        } catch (QueryException $e) {
            $code = $e->errorInfo[1] ?? null;
            if (in_array($code, [1451, 547])) {
                return response()->json([
                    'message' => 'Cannot delete category, referenced by another record.',
                ], 409);
            }

            return response()->json([
                'message' => 'Database error while deleting category.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
