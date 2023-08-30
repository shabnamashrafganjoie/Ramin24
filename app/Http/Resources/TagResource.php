<?php

namespace App\Http\Resources;

use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [


            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description , 
            'post_id' => $this->post_id , 
            'post' =>  new PostResource($this->whenLoaded('post')) ,


         

        ];    }
}
