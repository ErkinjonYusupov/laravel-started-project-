<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRuleResource extends JsonResource
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
            'user' => $this->full_name,
            'organization' => $this->organization,
            'active' => $this->active,
            'rules' => $this->rules()
        ];
    }

    public function rules()
    {
       $collection = $this->user_rules->map(function($item){
            return ['code' => $item?->rule?->code];
        });
        $data = [];
        foreach($collection as $item){
            $data[$item['code']] = true;
        }
        return $data;
    }
}
