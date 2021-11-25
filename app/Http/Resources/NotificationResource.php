<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'title' => $this->data['title'],
                'message' => Str::limit($this->data['message'], 30),
                'date' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
                'read' => $this->read_at === NULL ? 0 : 1,
            ];
    }
}
