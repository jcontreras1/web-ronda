<?php

namespace App\Http\Resources\Tarea;

use Illuminate\Http\Resources\Json\JsonResource;

class TareaResource extends JsonResource
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
            'renovable' => $this->renovable,
            'responsable_id' => $this->responsable_id,
            'responsable' => 
            [
                'id' => $this->responsable_id ?? null,
                'name' => $this->responsable_id ? ucwords($this->responsable->lastname . ' ' . $this->responsable->name) : null
            ],
            'creador' => 
            [
                'id' => $this->creador_id ?? null,
                'name' => $this->creador_id ? $this->creador->lastname . ' ' . $this->creador->name : null
            ],
            'created_at_human' => $this->created_at->diffForHumans(),
            'created_at' => date('d/m/Y H:i', strtotime($this->created_at)),
            'prioridad' => 
            [
                'descripcion' => $this->prioridad_id ? $this->prioridad->descripcion : null,
                'color' => $this->prioridad_id ? $this->prioridad->color : null,
            ],
            'titulo' => $this->titulo
        ];
    }
}
