<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Post;
use App\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = Post::all();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title'=>'required|max:250',
            'content' => 'required',
            'category_id' => 'exists:categories,id', // ci assicuriamo che o sia nulla o che esista
        ], [
            'title.required' => 'Il campo è obbligatorio',
            'content.required' => 'Il campo è obbligatorio',
            'category_id.exists' => 'La categoria non esiste'
        ]);

        $postData = $request->all();
        $newPost = new Post;
        $newPost->fill($postData);
        $slug = Str::slug($newPost->title);
        $alternativeSlug = $slug;

        $postFound = Post::where('slug', $slug)->first();

        $counter = 1;
        while ($postFound){
            $alternativeSlug = $slug . '_' . $counter;
            $counter++;
            $postFound = Post::where('slug', $alternativeSlug)->first();
        }

        $newPost->slug = $alternativeSlug;
        $newPost->save();
        return redirect()->route('admin.posts.show', $newPost->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::findOrFail($id);
        // $category = Category::find($post->category_id); // per trovare la categoria
        // return view('admin.posts.show', compact('post', 'category')); // senza interrogare category, in show utilizzo il metodoto category per recuperare il dato
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::findOrFail($id);
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'title'=>'required|max:250',
            'content' => 'required',
            'category_id' => 'exists:categories,id', // ci assicuriamo che o sia nulla o che esista
        ], [
            'title.required' => 'Il campo è obbligatorio',
            'content.required' => 'Il campo è obbligatorio',
            'category_id.exist' => 'Campo obbligatorio'
        ]);

        $postData = $request->all();
        $editedPost = Post::findOrFail($id);
        $editedPost->fill($postData);

        $slug = Str::slug($editedPost->title);
        $alternativeSlug = $slug;

        $postFound = Post::where('slug', $slug)->first();

        $counter = 1;
        while ($postFound){
            $alternativeSlug = $slug . '_' . $counter;
            $counter++;
            $postFound = Post::where('slug', $alternativeSlug)->first();
        }

        $editedPost->slug = $alternativeSlug;
        $editedPost->update();
        return redirect()->route('admin.posts.show', $editedPost->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
