<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
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
                'trx_id' => $this->trx_id,
                'trx_number' => $this->ref_no,
                'source' => $this->source ? $this->source->name : '-',
                'amount' => number_format($this->amount, 2) . ' MMK',
                'type' => $this->type,
                'description' => $this->description,
                'date' =>  Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            ];
    }
}
