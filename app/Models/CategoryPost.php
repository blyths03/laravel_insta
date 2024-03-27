<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;

    protected $table = 'category_post';
    protected $fillable = ['category_id', 'post_id'];
    // $fillable means mass assignment, multiple entry
    // you can choose many categories for the same post
    public $timestamps = false;
    // if you dont type false here, error will occur since laravel include $timestamps in table

    #get a name of the category
    public function category(){
        return $this->belongsTo(Category::class);
    }
}

/*
  [1,1] :post_id is 1 , category_id is 1
  [1,2] :still in the first post, second category 2
---- posts(many) to catetory(many)
--- this means you can choose many categories for the same post
*/
