<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\Response;
/**
 * @group Authenticated requests
 *
 * @authenticated
 */
class AuthorController extends Controller
{

    public function __construct() {
        $this->authorizeResource(Author::class, 'author');
    }

    public function index()
    {
        $authors = Author::withCount('news')->paginate(10);
        return AuthorResource::collection($authors);
    }

    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());
        return (new AuthorResource($author))
               ->response()
               ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Author $author)
    {
        $author->loadCount('news');
        return new AuthorResource($author);
    }

    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());
        return new AuthorResource($author);
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return response()->noContent();
    }
}
