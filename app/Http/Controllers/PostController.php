<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResourceColl;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostController extends Controller
{
    public function create(PostCreateRequest $request): PostResource
    {
        $data = $request->validated();

        $post = new Post($data);
        $post->slug = Str::slug($data['title']);
        $post->author_id = Auth::user()->id;
        $post->save();

        return new PostResource($post);
    }

    public function update(PostUpdateRequest $request, string $slug): PostResource
    {
        $data = $request->validated();
        $post = Post::firstWhere('slug', $slug);
        $user = Auth::user();

        if (!$post) {
            throw new HttpResponseException(response([
                'errors' => [
                    'post not found'
                ]
            ], 404));
        } elseif ($post->author_id != $user->id) {
            throw new HttpResponseException(response([
                'errors' => [
                    'Forbidden: Not the author of this post.'
                ]
            ], 403));
        }

        if (!empty($data['title'])) {
            $post->title = $data['title'];
            $post->slug = Str::slug($data['title']);
        }

        if (!empty($data['content'])) {
            $post->content = $data['content'];
        }

        if (!empty($data['category_id'])) {
            $post->category_id = $data['category_id'];
        }

        $post->save();

        return new PostResource($post);
    }

    public function show(string $slug): PostResource
    {
        $post = Post::firstWhere('slug', $slug);

        if (!$post) {
            throw new HttpResponseException(response([
                'errors' => [
                    'post not found'
                ]
            ], 404));
        }

        return new PostResource($post);
    }

    public function delete(string $slug): JsonResponse
    {
        $post = Post::firstWhere('slug', $slug);

        if (!$post) {
            throw new HttpResponseException(response([
                'errors' => [
                    'post not found'
                ]
            ], 404));
        }

        $post->delete();

        return response()->json([
            'data' => true
        ]);
    }

    public function listSearch(Request $request): PostResourceColl
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $posts = Post::query()->select('posts.*')
            ->leftJoin('users', 'posts.author_id', '=', 'users.id')
            ->leftJoin('categories', 'posts.category_id', '=', 'categories.id');

        if ($request->has('author')) {
            $posts->where('users.name', 'LIKE', '%' . $request->input('author') . '%');
        }

        if ($request->has('category')) {
            $posts->where('categories.name', 'LIKE', '%' . $request->input('category') . '%');
        }

        if ($request->has('title')) {
            $posts->where('posts.title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->has('slug')) {
            $posts->where('posts.slug', 'like', '%' . $request->input('slug') . '%');
        }

        $posts = $posts->paginate(perPage: $size, page: $page);

        return new PostResourceColl($posts);
    }
}
