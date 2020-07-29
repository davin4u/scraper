<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $id = $this->request->get('id', null);
        $name = $this->request->get('name', null);
        $email = $this->request->get('email', null);

        $users = User::find(1);

        if (!is_null($id)) {
            $users = $users->where('id', $id);
        }

        if (!is_null($name)) {
            $users = $users->where('name', 'like', "%{$name}%");
        }

        if (!is_null($email)) {
            $users = $users->where('email', 'like', "%{$email}%");
        }

        $users = $users->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserStoreRequest $request)
    {
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        return redirect(route('users.index'))->with(['status' => 'User has been created']);
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $user->update([
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password'))
        ]);

        return redirect(route('users.index'))->with(['status' => 'User has been changed']);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect(route('users.index'))->with(['status' => 'User has been deleted']);
    }
}
