<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\PostsException;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Retorna la vista de biblioteca
     *
     * @return View
     */
    public function index(): View
    {
        return view('biblioteca.index');   
    }

    /**
     * Consulta los post paginados para cargarlos en la tabla 
     *
     * @return JsonResponse
     */
    public function getPosts(Request $request): JsonResponse
    {
        try {
            $query = Post::query();

            $allowed = ['title', 'is_published', 'read_time', 'updated_at'];
            $sorted  = false;
            foreach ((array) $request->input('sort', []) as $s) {
                if (in_array($s['field'] ?? '', $allowed)) {
                    $query->orderBy($s['field'], ($s['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc');
                    $sorted = true;
                }
            }
            if (!$sorted) {
                $query->latest();
            }

            $size = max(1, min((int) $request->input('size', 15), 100));
            return response()->json($query->paginate($size));

        } catch (\Throwable $th) {
            throw new PostsException("Error al consultar los post: " . $th->getMessage());
        }
    }

    /**
     * Retorna la vista para crear un nuevo post
     *
     * @return View
     */
    public function create(): View
    {
        return view('biblioteca.create');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url|max:2048',
            'read_time' => 'required|integer|min:1',
            'categories' => 'nullable|array',
            'categories.*' => 'string|max:100',
        ]);

        Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'content' => $request->content,
            'image_url' => $request->image_url,
            'read_time' => $request->read_time,
            'category' => json_encode($request->categories ?? []), 
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('biblioteca.index')->with('success', 'Artículo creado correctamente.');
    }

    /**
     * Publica o despublica un post
     *
     * @param Request $request
     * @return void
     */
    public function togglePublish(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $post = Post::where('id', $request->id_post)->first();

            if(!$post){
                return response()->json([
                    "message" => "Post no encontrado"
                ], 404);    
            }
            $current_status = $post->is_published;
            $new_status = $current_status == 1 ? 0 : 1;
            $post->update([
                'is_published' => $new_status,
            ]);

            DB::commit();
            return response()->json([
                "message" => "Post cambiado de estado con exito!"
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new  PostsException("No se pudo publicar/despublicar el post");
        }
    }

    /**
     * Redirige a la view de edit post
     *
     * @param Post $post
     * @return void
     */
    public function edit(Post $post)
    {
        return view('biblioteca.edit', compact('post'));
    }

    /**
     * Actualiza un post
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'image_url'    => 'nullable|url|max:2048',
            'read_time'    => 'required|integer|min:1',
            'categories'   => 'nullable|array',
            'categories.*' => 'string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $post = Post::where('id', $request->id_post)->first();

            if(!$post){
                return response()->json([
                    "message" => "Post no encontrado"
                ], 404);    
            }

            $post->update([
                'title'        => $request->title,
                'slug'         => Str::slug($request->title) . '-' . Str::random(6),
                'content'      => $request->content,
                'image_url'    => $request->image_url,
                'read_time'    => $request->read_time,
                'category'     => json_encode($request->input('categories', [])),
                'is_published' => $request->boolean('is_published'),
            ]);

            DB::commit();
            return response()->json([
                "message" => "Post actualizado con exito!"
            ], 201);  
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new PostsException("Error al actualizar el post");
        }
    }

    /**
     * Eliminacion logica de un post
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
       DB::beginTransaction();

       try {

        $post = Post::where('id', $post->id)->first();

        if(!$post){
            return response()->json([
                'message' => 'Post no encontrado.'
            ], 404);
        }

        $post->delete();
        DB::commit();
        return response()->json([
            'message' => 'Post eliminado con exito.'
        ], 200);

       } catch (\Throwable $th) {
        DB::rollBack();
        throw new PostsException("Error al eliminar post");
       }

    }
    
}
