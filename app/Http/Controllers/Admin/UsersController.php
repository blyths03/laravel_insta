<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $all_users = $this->user->withTrashed()->latest()->paginate(3);
        // withTrashed(): include if users were temporalily deleted
        // if you just wanna show all, use get() instead of paginate()

        return view('admin.users.index')->with('all_users', $all_users);
    }

    public function deactivate($id)
    {
        $this->user->destroy($id);
        return redirect()->back();

        // if you use softdelete, destroy means temporarily delete data
           // using softdelete means including sofedelete in Models-user.php 
        // to delete data permanently, use delete() = forceDelete()

    }

    public function activate($id)
    {
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        // we can active already active-users, so we use onlytrashed() before restore
        return redirect()->back();
    }
}
