<?php

namespace App\Http\Resources;

use App\Enums\DeliveryMethodEnum;
use App\Models\Address;
use App\Models\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Company $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contract' => $this->contract,
            'payment_terms' => $this->payment_terms,
            'delivery_method' => $this->delivery_method,
            'users' => AccountUserResource::collection($this->users),
            'responsible' => $this->responsible,
            'manager' => $this->manager,
            'national_manager' => $this->national_manager,
            'addresses' => $this->delivery_method == DeliveryMethodEnum::DELIVERY ?
                $this->addresses :
                Address::default(),
        ];
    }
}
