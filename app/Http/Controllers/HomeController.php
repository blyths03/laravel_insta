<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    // it is better to create this in web.php so that we dont need to do this every Controller

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function __construct(Post $post, User $user){
        $this->post = $post;
        $this->user = $user;

    }

    public function index()
    {
        // $all_posts = $this->post->latest()->get();
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }



    #Get the posts of the users that the Auth user is following
    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = []; //In case the $home_posts is empty, it will not return null, but empty instead.

        foreach($all_posts as $post)
        {
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id)
            {
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    #Get the users that te Auth user is not following.
    private function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                // if users are not followed : see user.php
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users, 0, 3);
        //array_slice(x,y,z);
        // x - array name
        // y - offset/starting index
        // z - length/how many
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }
}
