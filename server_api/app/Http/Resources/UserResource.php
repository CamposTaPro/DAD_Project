<?php

namespace App\Http\Resources;


use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'type' => $this->type,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->photo_url,
            'customer' => CustomerResource::collection(Customer::where('user_id', $this->id)->get()),
            'blocked' => $this->blocked,
        ];
    }
}
