<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'datetime' => $this->datetime->format('Y-m-d H:i:s'),
            'open' => number_format($this->open, 4, '.', ''),
            'high' => number_format($this->high, 4, '.', ''),
            'low' => number_format($this->low, 4, '.', ''),
            'close' => number_format($this->close, 4, '.', ''),
            'volume' => (string) $this->volume,

        ];
    }
}
