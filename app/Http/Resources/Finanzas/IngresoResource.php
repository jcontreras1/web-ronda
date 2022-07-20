<?php

namespace App\Http\Resources\Finanzas;

use Illuminate\Http\Resources\Json\JsonResource;

class IngresoResource extends JsonResource
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
			'hora' => date('H:i', strtotime($this->created_at)),
			'monto' => $this->monto,
			'observaciones' => $this->observaciones,
			'medio_pago' => $this->medio_pago_id,
			'moneda' => $this->moneda_id,
		];
	}
}
