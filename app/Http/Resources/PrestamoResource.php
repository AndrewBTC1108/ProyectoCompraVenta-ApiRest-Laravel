<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoResource extends JsonResource
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
            'fecha' => $this->fecha,
            'valor_prestado' => $this->valor_prestado,
            'cuotas' => $this->cuotas,
            'bien_id' => $this->whenLoaded('bien'),
            'cliente_id' => $this->whenLoaded('cliente')
        ];
    }
}
