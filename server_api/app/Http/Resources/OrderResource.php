<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'ticket_number' => $this->ticket_number,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'total_price' => $this->total_price,
            'date' => $this->date,
            'payment_type' => $this->payment_type,
            //'delivered_by' => isset($this->delivered_by) ? User::find($this->delivered_by)->name : "",  //TODO - enviamos vazio ou outra coisa?
            'delivered_by' => isset(User::find($this->delivered_by)->name) ? User::find($this->delivered_by)->name : ""
        ];
    }
}
