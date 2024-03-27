<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category){
        // be careful to use double__ for this
        $this->post = $post;
        $this->category = $category;
    }

    public function create(){
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }


    public function store(Request $request)
    {
        # 1. Validate all form data
        $request->validate([
            'category'      =>      'required|array|between:1,3',
            'description'   =>      'required|min:1|max:1000',
            'image'         =>      'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        # 2. Save the post
        $this->post->user_id        =   Auth::user()->id;
        $this->post->image          =   'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description    =   $request->description;
        $this->post->save();

        # 3. Save the categories to the category_post table
        foreach($request->category as $category_id){
            $category_post[]    =   ['category_id' => $category_id];
        }

        $this->post->categoryPost()->createMany($category_post);

        # 4. Go back to homepage
        return redirect()->route('index');
    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
                ->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        #If the Auth yser is not the owner of the post, redirect to homepage
        if(Auth::user()->id != $post->user->id){
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        #get all the category IDs of this post. Save in an array.
        $selected_categories = [];
        // categories will be appear checked, categories checked by you
        foreach($post->categoryPost as $category_post){
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
                    ->with('post', $post)
                    ->with('all_categories', $all_categories)
                    ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id)
    {
        # 1. Validate the data from the form
        $request->validate([
            'category'      =>  'required|array|between:1,3',
            'description'   =>  'required|min:1|max:1000',
            'image'         =>  'mimes:jpg,png,jpeg,gif|max:1048'
        ]);

        # 2. Update the post
        $post               = $this->post->findOrFail($id);
        // find specific post

        $post->description  = $request->description;

        //If there is a new image
        if($request->image)
        {
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        # 3. Delete all the records from category_post related to this post
        $post->categoryPost()->delete();
        //Use the relationship Post::categoryPost() to select the records related to a post
        //Equivalent: DELETE from category_post WHERE post_id = $id

        # 4. Save the new categories to category_post table
        foreach($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }

        $post->categoryPost()->createMany($category_post);

        # 5. Redirect to Show Post page (to confirm the update)
        return redirect()->route('post.show', $id);
    }

        public function destroy($id)
        {
            // $this->post->destroy($id);
            // return redirect()->route('index');
                // above will not be used anymore since we include softdelete. if you want delete posts permanently use below

            $post = $this->post->findOrFail($id);
            $post->forceDelete();
            return redirect()->route('index');
        }

}