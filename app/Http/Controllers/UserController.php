<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = Users::all();
        return view('admin.user.index', compact('data'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        Users::create($request->all());
        return redirect()->route('user.index');
    }

    public function edit(Users $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, Users $user)
    {
        $user->update($request->all());
        return redirect()->route('user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index');
    }
}
