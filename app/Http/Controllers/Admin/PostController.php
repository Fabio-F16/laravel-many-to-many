<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Post;
use App\Category;
use App\Tag;

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
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
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
            'category_id' => 'exists:categories,id',
            'tags[]' => 'exists:tags,id', // ci assicuriamo che o sia nulla o che esista
            'image' => 'nullable|image'
        ], [
            'title.required' => 'Il campo è obbligatorio',
            'content.required' => 'Il campo è obbligatorio',
            'category_id.exists' => 'La categoria non esiste',
            'tags[]' => 'Tag non esiste',
            'image' => 'Il file deve essere un\'immagine'
        ]);

        $postData = $request->all();

        if(array_key_exists('image', $postData)){
            $img_path = Storage::put('uploads', $postData['image']);
            $postData['cover'] = $img_path;
        }
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


        // prima di aggiungere i tag bisogna salvare la prima parte dei dati ricevuti,
        // altrimenti non si potrebbe creare l'associazione nella tabella pivot
        $newPost->save();
        // add tags
        if(array_key_exists('tags', $postData)){
            $newPost->tags()->sync($postData['tags']);
        }

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
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
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
            'tags' => 'exists:tags,id'
        ], [
            'title.required' => 'Il campo è obbligatorio',
            'content.required' => 'Il campo è obbligatorio',
            'category_id.exist' => 'Campo obbligatorio',
            'tags[]' => 'Tag non esistente'
        ]);

        $postData = $request->all();

        $editedPost = Post::findOrFail($id);

        // immagine upload
        if(array_key_exists('image', $postData)){
            Storage::delete($editedPost->cover);
            $img_path = Storage::put('uploads', $postData['image']);
            $postData['cover'] = $img_path;
        }


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

        // prima di aggiungere i tag bisogna salvare la prima parte dei dati ricevuti,
        // altrimenti non si potrebbe creare l'associazione nella tabella pivot
        $editedPost->save();
        // add tags
        if(array_key_exists('tags', $postData)){
            $editedPost->tags()->sync($postData['tags']);
        }

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
        $post->tags()->sync([]); // per cancellare nella tabella pivot
        if($post->cover){
            Storage::delete($post->cover);
        }
        $post->delete();

        return redirect()->route('admin.posts.index');
    }
}
