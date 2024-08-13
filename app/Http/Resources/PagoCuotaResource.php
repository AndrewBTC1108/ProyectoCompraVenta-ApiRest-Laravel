<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagoCuotaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'prestamo_id' => new PrestamoResource($this->whenLoaded('prestamo')),
            'fecha_pago' => $this->fecha_pago,
            'monto_pago' => $this->monto_pago,
        ];
    }
}
