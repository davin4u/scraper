<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewAuthorUpdateRequest;
use App\ReviewAuthor;
use Illuminate\Http\Request;

class ReviewAuthorsController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * UsersController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $id = $this->request->get('id', null);
        $name = $this->request->get('name', null);
        $platform = $this->request->get('platform', null);

        $reviewAuthors = ReviewAuthor::query()->orderBy('id');

        if (!is_null($id)) {
            $reviewAuthors->where('id', $id);
        }

        if (!is_null($name)) {
            $reviewAuthors->where('name', 'like', "%{$name}%");
        }

        if (!is_null($platform)) {
            $reviewAuthors->where('platform', 'like', "%{$platform}%");
        };

        $reviewAuthors = $reviewAuthors->paginate(30);

        return view('authors.index')->with([
            'reviewAuthors' => $reviewAuthors->appends(\request()->except('page')),
            'id' => $id,
            'name' => $name,
            'platform' => $platform
        ]);
    }

    /**
     * @param ReviewAuthor $reviewAuthor
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ReviewAuthor $reviewAuthor)
    {
        return view('authors.edit', compact('reviewAuthor'));
    }

    /**
     * @param ReviewAuthor $reviewAuthor
     * @param ReviewAuthorUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ReviewAuthor $reviewAuthor, ReviewAuthorUpdateRequest $request)
    {
        $reviewAuthor->update([
            'name' => $request->get('name'),
            'platform' => $request->get('platform'),
            'rating' => $request->get('rating'),
            'total_reviews' => $request->get('total_reviews'),
            'profile_url' => $request->get('profile_url'),
        ]);

        return redirect(route('authors.index'))->with(['status' => 'Author has been updated']);
    }

    /**
     * @param ReviewAuthor $reviewAuthor
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(ReviewAuthor $reviewAuthor)
    {
        $reviewAuthor->delete();

        return redirect(route('authors.index'))->with(['status' => 'Author has been deleted']);
    }
}
