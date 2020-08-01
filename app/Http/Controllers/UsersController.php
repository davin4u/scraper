<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
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
        $email = $this->request->get('email', null);

        $users = User::query()->orderBy('id');

        if (!is_null($id)) {
            $users->where('id', $id);
        }

        if (!is_null($name)) {
            $users->where('name', 'like', "%{$name}%");
        }

        if (!is_null($email)) {
            $users->where('email', 'like', "%{$email}%");
        }

        $users = $users->paginate(30);

        return view('users.index')->with([
            'users' => $users->appends(\request()->except('page')),
            'id' => $id,
            'name' => $name,
            'email' => $email
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * @param UserStoreRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(UserStoreRequest $request)
    {
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        return redirect(route('users.index'))->with(['status' => 'User has been created']);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * @param User $user
     * @param UserUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(User $user, UserUpdateRequest $request)
    {
        $user->update([
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password'))
        ]);

        return redirect(route('users.index'))->with(['status' => 'User has been changed']);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect(route('users.index'))->with(['status' => 'User has been deleted']);
    }
}
