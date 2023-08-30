<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TagResource;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

class TagController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags=Tag::paginate(10);

        return $this->successResponse([
            'tags' => TagResource::collection($tags),
            'links' => TagResource::collection($tags)->response()->getData()->links,
            'meta' => TagResource::collection($tags)->response()->getData()->meta,
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
            'description' => 'required|max:500|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
            'post_id' => 'min:1|max:100000000|regex:/^[0-9]+$/u|exists:tags,id'


        ]);

        if($validator ->fails()){

            return $this->errorResponse($validator->messages(),422);
        }

        //dd('hi2');

        DB::BeginTransaction();
        $tag = Tag::create([
            'name' => $request -> name,
            'description' => $request->description,
            'post_id' => $request->post_id
        ]);
        DB::commit();

        return $this->successResponse(new TagResource($tag),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag=Tag::findOrFail($id);
        return $this->successResponse(new TagResource($tag) );
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
            'description' => 'max:500|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,!><\/;\n\r& ]+$/u',
            'post_id' => 'min:1|max:100000000|regex:/^[0-9]+$/u|exists:products,id'


        ]);

        if($validator ->fails()){

            return $this->errorResponse($validator->messages(),422);
        }


        DB::BeginTransaction();
        $tag=Tag::findOrFail($id);

        $tag ->update([
            'name' => $request -> name,
            'description' => $request->description,
        ]);
        DB::commit();

        return $this->successResponse(new TagResource($tag),200);
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

        $tag=Tag::findOrFail($id);


        $tag->delete();
        DB::commit();

        return $this->successResponse(new TagResource($tag),200);
    }



    public function post($id)
    {
       $tag=Tag::findOrFail($id);
     //  dd($this->successResponse(new TagResource($tag->post)));
        return $this->successResponse(new TagResource($tag->load('post')) );

    }
}
