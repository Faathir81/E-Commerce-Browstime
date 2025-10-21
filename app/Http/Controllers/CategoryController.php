<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\Catalog\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * List categories with optional q, sort, pagination.
     */
    public function index(Request $request)
    {
        try {
            $query = Category::query();

            if ($q = $request->query('q')) {
                $query->where(function ($b) use ($q) {
                    $b->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
                });
            }

            $allowedSorts = [
                'name_asc' => ['name', 'asc'],
                'name_desc' => ['name', 'desc'],
                'created_asc' => ['created_at', 'asc'],
                'created_desc' => ['created_at', 'desc'],
            ];

            $sortKey = $request->query('sort', 'created_desc');
            if (!isset($allowedSorts[$sortKey])) {
                $sortKey = 'created_desc';
            }

            [$sortColumn, $sortDir] = $allowedSorts[$sortKey];
            $query->orderBy($sortColumn, $sortDir);

            $perPage = (int) $request->query('per_page', 10);
            if ($perPage < 1) $perPage = 10;
            if ($perPage > 50) $perPage = 50;

            $page = (int) $request->query('page', 1);
            if ($page < 1) $page = 1;

            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $paginator->getCollection()->values()->all(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Show category by id.
     */
    public function show(int $id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json(['data' => $category], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Store category.
     */
    public function store(StoreCategoryRequest $request)
    {
        $payload = $request->validated();

        // light normalization
        $payload['name'] = trim($payload['name']);
        if (isset($payload['slug'])) {
            $payload['slug'] = strtolower(trim($payload['slug']));
        }

        try {
            $category = $this->categoryService->create($payload);
            return response()->json(['data' => $category], 201);
        } catch (QueryException $e) {
            $code = $e->errorInfo[1] ?? $e->getCode();
            // 1062 duplicate entry -> conflict
            if ($code == 1062) {
                return response()->json(['message' => 'Conflict'], 409);
            }
            return response()->json(['message' => 'Could not create category'], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Update category.
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $payload = $request->validated();

        if (isset($payload['name'])) {
            $payload['name'] = trim($payload['name']);
        }
        if (isset($payload['slug'])) {
            $payload['slug'] = strtolower(trim($payload['slug']));
        }

        try {
            $updated = $this->categoryService->update($id, $payload);
            return response()->json(['data' => $updated], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        } catch (QueryException $e) {
            $code = $e->errorInfo[1] ?? $e->getCode();
            // FK or constraint violation -> 409
            if ($code == 1451 || $code == 547) {
                Log::warning('Category update constraint violation', ['category_id' => $id, 'error' => $e->getMessage()]);
                return response()->json(['message' => 'Conflict'], 409);
            }
            return response()->json(['message' => 'Could not update category'], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Delete category.
     */
    public function destroy(int $id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check products using this category
            $productsCount = $category->products()->count();
            if ($productsCount > 0) {
                return response()->json([
                    'message' => 'Category is in use and cannot be deleted.',
                    'details' => ['products_count' => $productsCount],
                ], 409);
            }

            $this->categoryService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        } catch (QueryException $e) {
            $code = $e->errorInfo[1] ?? $e->getCode();
            if ($code == 1451 || $code == 547) {
                Log::warning('Category delete failed due to foreign key constraint', ['category_id' => $id, 'error' => $e->getMessage()]);
                return response()->json(['message' => 'Resource cannot be deleted due to constraint'], 409);
            }
            return response()->json(['message' => 'Could not delete category'], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}