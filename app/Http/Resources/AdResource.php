<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   public function toArray($request)
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'description' => $this->description,
        'price' => $this->price,
        'category' => $this->category->name,
        'fields' => $this->fieldValues->mapWithKeys(function($fv){
            return [$fv->field->name => $fv->value];
        }),
        'created_at' => $this->created_at,
    ];
}

}
