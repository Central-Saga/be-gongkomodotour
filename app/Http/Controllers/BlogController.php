<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Http/Controllers/BlogController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Http\Resources\BlogResource;
use App\Services\Contracts\BlogServiceInterface;

class BlogController extends Controller
{
    /**
     * @var BlogServiceInterface $blogService
     */
    protected $blogService;

    /**
     * Konstruktor BlogController.
     *
     * @param BlogServiceInterface $blogService
     */
    public function __construct(BlogServiceInterface $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Display a listing of the blogs.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // Ambil parameter status dan kategori dari query string
        $status = $request->query('status');
        $category = $request->query('category');

        if ($category) {
            // Jika ada kategori, ambil blog berdasarkan kategori
            $blogs = $this->blogService->getBlogByCategory($category);
        } elseif ($status === 1) {
            // Jika status = published, ambil blog yang dipublikasikan
            $blogs = $this->blogService->getPublishedBlog();
        } elseif ($status === 0) {
            // Jika status = draft, ambil blog berstatus draft
            $blogs = $this->blogService->getDraftBlog();
        } else {
            // Jika tidak ada parameter, ambil semua blog
            $blogs = $this->blogService->getAllBlog();
        }

        return BlogResource::collection($blogs);
    }

    /**
     * Store a newly created blog in storage.
     *
     * @param BlogStoreRequest $request
     * @return BlogResource
     */
    public function store(BlogStoreRequest $request)
    {
        $blog = $this->blogService->createBlog($request->validated());
        return new BlogResource($blog);
    }

    /**
     * Display the specified blog.
     *
     * @param string $id
     * @return BlogResource|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $blog = $this->blogService->getBlogById($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        return new BlogResource($blog);
    }

    /**
     * Update the specified blog in storage.
     *
     * @param BlogUpdateRequest $request
     * @param string $id
     * @return BlogResource|\Illuminate\Http\JsonResponse
     */
    public function update(BlogUpdateRequest $request, string $id)
    {
        $blog = $this->blogService->updateBlog($id, $request->validated());
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        return new BlogResource($blog);
    }

    /**
     * Remove the specified blog from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $deleted = $this->blogService->deleteBlog($id);
        if (!$deleted) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        return response()->json(['message' => 'Blog deleted successfully'], 200);
    }
}
