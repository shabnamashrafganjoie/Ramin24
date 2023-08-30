<?php

namespace App\Http\Resources;

use App\Http\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'tags' =>  TagResource::collection($this->whenLoaded('tags')) ,
         

        ];
    }
}
