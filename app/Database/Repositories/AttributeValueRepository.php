<?php


namespace App\Database\Repositories;

use App\Database\Models\AttributeValue;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class AttributeValueRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'value'        => 'like',
        'shop_id',
        'language',
    ];

    protected $dataArray = [
        'value',
        'attribute_id',
        'slug',
        'language',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'position',
        'cover_image'
    ];
    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AttributeValue::class;
    }
    public function storeAttributeValue($request)
    {
        $attribute = $this->create($request->only($this->dataArray));
        return $attribute;
    }
    public function updateAttributeValue($request, $attributeValue)
    {
        $attributeValue->update($request->only($this->dataArray));
        return $this->with('attribute')->findOrFail($attributeValue->id);
    }
}
