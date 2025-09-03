<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use function Pest\Laravel\json;

class CategoryController extends Controller
{
    // public function list()

    public function create(CategoryCreateRequest $request): CategoryResource
    {
        $data = $request->validated();

        $category = new Category($data);
        $category->slug = Str::slug($data['name']);
        $category->save();

        return new CategoryResource($category);
    }

    public function show(string $slug): CategoryResource
    {
        $category = Category::firstWhere('slug', $slug);
        if (!$category) {
            throw new HttpResponseException(response([
                'errors' => [
                    'category not found'
                ]
            ], 404));
        }

        return new CategoryResource($category);
    }

    public function update(CategoryUpdateRequest $request, string $slug): CategoryResource
    {
        $data = $request->validated();

        $category = Category::firstWhere('slug', $slug);

        if (!$category) {
            throw new HttpResponseException(response([
                'errors' => [
                    'category not found'
                ]
            ], 404));
        }

        $category->name = $data['name'];
        $category->slug = Str::slug($data['name']);
        $category->save();

        return new CategoryResource($category);
    }

    public function delete(string $slug): JsonResponse
    {
        $category = Category::firstWhere('slug', $slug);

        if (!$category) {
            throw new HttpResponseException(response([
                'errors' => [
                    'category not found'
                ]
            ], 404));
        }

        $category->delete();

        return response()->json([
            'data' => true
        ]);
    }

    public function list(): JsonResponse
    {
        $category = Category::orderBy('name', 'asc')->get();
        if ($category->count() == 0) {
            throw new HttpResponseException(response([
                'errors' => [
                    'no category data is available at the moment.'
                ]
            ], 404));
        }

        return CategoryResource::collection($category)->response()->setStatusCode(200);
    }
}
