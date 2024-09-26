<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\SessionResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @property SessionResult $resource
 */
class SessionResultResource extends JsonResource
{
    public function __construct(SessionResult $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $drivers = $this->resource->getDrivers();

        return [
            'p1' => $drivers->firstWhere('id', $this->resource->p1_id),
            'p2' => $drivers->firstWhere('id', $this->resource->p2_id),
            'p3' => $drivers->firstWhere('id', $this->resource->p3_id),
            'p4' => $drivers->firstWhere('id', $this->resource->p4_id),
            'p5' => $drivers->firstWhere('id', $this->resource->p5_id),
            'p6' => $drivers->firstWhere('id', $this->resource->p6_id),
            'p7' => $drivers->firstWhere('id', $this->resource->p7_id),
            'p8' => $drivers->firstWhere('id', $this->resource->p8_id),
            'p9' => $drivers->firstWhere('id', $this->resource->p9_id),
            'p10' => $drivers->firstWhere('id', $this->resource->p10_id),
        ];
    }
}
