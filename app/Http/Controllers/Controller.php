<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getTreeOrganizationId($organizationId)
    {
        $organization = Organization::with(['children'])
        ->where('id', $organizationId)
        ->first();
        $organizations[] = [
            'id' => $organization->id,
        ];
       return $organizations = $this->recOrganization($organizations, $organization->children);
    }

    public function recOrganization($organizations, $children, )
    {
        foreach($children as $child){
            $organizations[] = [
                'id' => $child['id'],
            ];
            if($this->checkChild($child->children)){
                $organizations = $this->recOrganization($organizations, $child['children']);
            }
        }
        return $organizations;
    }

    public function checkChild($children)
    {
        return count($children ?? []) > 0 ? true : false;
    }
}
