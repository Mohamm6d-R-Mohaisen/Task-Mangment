<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Admin\ProjectResource;
use App\Models\Image;
use App\Traits\CommonQueryScopes;

class Project extends Model
{
use CommonQueryScopes;
    public $resource=ProjectResource::class;
    protected $fillable = [
         'title',
            'description',
            'start_date',
            'end_date'  ,
            'created_by',


    ];
    protected $casts = [
        'features' => 'array',

    ];
    public function scopeSearch($query, $request)
    {
        if (!empty($request->search['value'])) {
            $search = '%' . $request->search['value'] . '%';
            return $query->where(function($r) use ($search){
                $r->where('name', 'LIKE', $search);

            });
        }
        return $query;
    }

}
