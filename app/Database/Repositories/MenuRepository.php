<?php


namespace App\Database\Repositories;

use App\Models\Menus;
use App\Database\Models\Attribute;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use App\Database\Models\AttributeValue;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class MenuRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'        => 'like',
        'shop_id',
        'language',
    ];

    protected $dataArray = [
        'name',
        'public_name',
        'slug',
        'shop_id',
        'language',
        'group_type',
        'position'
    ];

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
            //
        }
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Menus::class;
    }
}
