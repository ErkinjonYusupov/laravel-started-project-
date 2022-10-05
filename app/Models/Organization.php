<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'parent_id'
    ];

    public function parent()
    {
        return $this->hasOne(Organization::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id', 'id')->with(['children']);
    }
}
