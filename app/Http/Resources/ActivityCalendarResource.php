<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityCalendarResource extends JsonResource
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
            'title' => $this->title,
            'tooltip' => $this->description,
            'url' => '',
            'image' => (strpos($this->image, 'https://') !== false || strpos($this->image, 'http://') !== false) || is_null($this->image) ? $this->image : asset("storage/{$this->image}"),
            'startDate' => ['year' => $this->activity_date->format('Y'), 'month' => $this->activity_date->format('n'), 'day' => $this->activity_date->format('d')]
        ];
    }
}
