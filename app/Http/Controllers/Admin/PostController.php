<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        $data = [
            'posts' => $posts
        ];

        return view('admin.posts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|max:60000'
        ]);
        
        $form_data = $request->all();
        
        $new_post = new Post();
        $new_post->fill($form_data);

        $new_post->slug = $this->getFreeSlugFromTitle($new_post->title);

        $new_post->save();
        
        return redirect()->route('admin.posts.show', ['post' => $new_post->id]);
    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        $data = [
            'post' => $post
        ];

        return view('admin.posts.show', $data);
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
    }

    // Genera uno slug unico dal titolo
    protected function getFreeSlugFromTitle($title) {
         // Assegno lo slug ad una variabile
         $slug_to_save = Str::slug($title, '-');
         // Salvo uno slug base uguale a quello da salvare
         $slug_base = $slug_to_save;
         // Verifico se questo slug è presente nel database
         $existing_slug_post = Post::where('slug', '=', $slug_to_save)->first();
         // Finchè non trovo uno slug libero, appendo un numero allo slug base
         $counter = 1;

         while($existing_slug_post) {
            // Creo un nuovo slug con $counter
            $slug_to_save = $slug_base . '-' . $counter;
            // Verifico se questo slug è presente nel database
            $existing_slug_post = Post::where('slug', '=', $slug_to_save)->first();
            // incremento il counter
            $counter++;
        }
        return $slug_to_save;
    }
}