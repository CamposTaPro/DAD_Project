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

        //dd($request);
        //dd(User::find($this->delivered_by)->name);
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'total_price' => $this->total_price,
            'date' => $this->date,
            'payment_type' => $this->payment_type,
            //'delivered_by' => User::find($this->delivered_by)->name,
            'delivered_by' => User::find($this->delivered_by),  //TODO

        ];
    }

    function getDeliverName($id){
        return User::find($id)->name;
    }
}
