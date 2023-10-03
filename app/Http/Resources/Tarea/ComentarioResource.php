<?php

namespace App\Http\Resources\Tarea;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ComentarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->creador),
            'comentario' => $this->comentario,
            'fijado' => $this->fijado,
            'created_at_human' => $this->created_at->diffForHumans(),
            'created_at' => date('d/m/Y H:i', strtotime($this->created_at))
        ];
    }
}
