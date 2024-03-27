<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like){
        $this->like = $like;
    }

    public function store($post_id){
        $this->like->user_id = Auth::user()->id;
        $this->like->post_id = $post_id;
        $this->like->save();

        return redirect()->back();
    }

    public function destroy($post_id)
    {
        $this->like
                ->where('post_id', $post_id)
                ->where('user_id', Auth::user()->id)

        // findOrFail(): inside of (), there should be one parameter, therefore here we cannot use this
                
                ->delete();

                return redirect()->back();
    }
}
