<?php

namespace App\Http\Resources\Finanzas;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            'venta'     => $this->venta_id,
            'agencia'   => $this->agencia_id,
            'numero'    => $this->numero,
            'rendido'   => $this->rendido,
            'comision'  => $this->comision,
            'venta'     => $this->venta,
        ];
    }
}
