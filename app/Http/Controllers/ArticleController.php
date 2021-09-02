<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleFilterRequest;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ArticleFilterRequest $request)
    {
	    $query = Article::with('ratings');

	    if ($request->filled('start_day')) {

		    $date_from = Carbon::parse($request->input('start_day'))->startOfDay();
		    $query->where('created_at', '>=', $date_from);
	    }

	    if ($request->filled('end_day')) {

		    $date_to = Carbon::parse($request->input('end_day'))->endOfDay();
		    $query->where('created_at', '<=', $date_to);
	    }

		return ArticleResource::collection($query->paginate());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return ArticleResource|\Illuminate\Http\JsonResponse|object
     */
    public function store(ArticleStoreRequest $request)
    {
		$article = Article::create([
					'user_id'     => $request->user()->id,
					'title'       => $request->title,
					'description' => $request->description,
		]);

		return (new ArticleResource($article))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return ArticleResource
     */
    public function show(Article $article)
    {

        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return ArticleResource|\Illuminate\Http\JsonResponse|object
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
		$article->update($request->only(['title', 'description']));

		return (new ArticleResource($article))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Article $article)
    {
	    $article->delete();

	    return response()->json( null, 204 );
    }
}
