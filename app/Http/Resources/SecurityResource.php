<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\RolResource;
use Illuminate\Support\Arr;

class SecurityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [];
        if ($this->token)
        {
            $array = [
                "id" => $this->id,
                "name" => $this->name,
                "last_name" => $this->last_name,
                "username" => $this->username,
                "email" => $this->email,
                "token" => $this->token,
                "status" => $this->status,
                "roles" =>  $this->getRoleNames()
            ];
        }
        else
        {
            $array = ['message' => $this->message];
        }
        return $array;
    }
}
