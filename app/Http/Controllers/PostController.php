<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $posts=Post::paginate(10);
        return $this->successResponse([
            'posts' => PostResource::collection($posts),
            'links' => PostResource::collection($posts)->response()->getData()->links,
            'meta' => PostResource::collection($posts)->response()->getData()->meta,
        ]);



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator=Validator::make($request->all(),[

            'name' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u' ,
            'description' => 'required|max:500|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u'

        ]);

        if($validator ->fails()){

            return $this->errorResponse($validator->messages(),422);
        }


        DB::BeginTransaction();
        $post = Post::create([
            'name' => $request -> name,
            'description' => $request->description,
        ]);
        DB::commit();

        return $this->successResponse(new PostResource($post),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $post=Post::findOrFail($id);
        return $this->successResponse(new PostResource($post) );

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
        $validator=Validator::make($request->all(),[

            'name' => 'max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u' ,
            'description' => 'max:500|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,!><\/;\n\r& ]+$/u'

        ]);

        if($validator ->fails()){

            return $this->errorResponse($validator->messages(),422);
        }


        DB::BeginTransaction();
        $post=Post::findOrFail($id);

        $post ->update([
            'name' => $request -> name,
            'description' => $request->description,
        ]);
        DB::commit();

        return $this->successResponse(new PostResource($post),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::BeginTransaction();

        $post=Post::findOrFail($id);


        $post->delete();
        DB::commit();

        return $this->successResponse(new PostResource($post),200);
    }


    public function tags($id)
    {
       $post=Post::findOrFail($id);
        return $this->successResponse(new PostResource($post->load('tags')) );

    }
}
