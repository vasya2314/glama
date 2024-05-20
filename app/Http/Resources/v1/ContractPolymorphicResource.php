<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractPolymorphicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->resource->getTable() == 'legal_entities')
        {
            return $this->getLegalEntityResponse();
        }

        if($this->resource->getTable() == 'individual_entrepreneurs')
        {
            return $this->getIndividualEntrepreneurResponse();
        }

        if($this->resource->getTable() == 'natural_persons')
        {
            return $this->getNaturalPersonResponse();
        }

        return [];
    }

    private function getLegalEntityResponse(): array
    {
        return [
            'id' => $this->id,
            'inn' => $this->inn,
            'kpp' => $this->kpp,
            'ogrn' => $this->ogrn,
            'company_name' => $this->company_name,
            'legal_address' => $this->legal_address,
            'actual_address' => $this->actual_address,
            'contact_face' => $this->contact_face,
            'job_title' => $this->job_title,
            'phone' => $this->phone,
            'email' => $this->email,
            'bik' => $this->bik,
            'checking_account' => $this->checking_account,
            'bank_name' => $this->bank_name,
            'correspondent_account' => $this->correspondent_account,
            'pick_up' => $this->pick_up,
            'is_same_legal_address' => $this->is_same_legal_address,
        ];
    }

    private function getIndividualEntrepreneurResponse(): array
    {
        return [
            'id' => $this->id,
            'inn' => $this->inn,
            'ogrnip' => $this->ogrnip,
            'company_name' => $this->company_name,
            'legal_address' => $this->legal_address,
            'actual_address' => $this->actual_address,
            'contact_face' => $this->contact_face,
            'job_title' => $this->job_title,
            'phone' => $this->phone,
            'email' => $this->email,
            'bik' => $this->bik,
            'checking_account' => $this->checking_account,
            'bank_name' => $this->bank_name,
            'correspondent_account' => $this->correspondent_account,
            'pick_up' => $this->pick_up,
            'is_same_legal_address' => $this->is_same_legal_address,
        ];
    }

    private function getNaturalPersonResponse(): array
    {
        return [
            'id' => $this->id,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'inn' => $this->inn,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'pick_up' => $this->pick_up,
        ];
    }

}
