<?php

namespace Indapoint\EmailTemplate\app\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'notification_templates';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get template data
     * @param type $where
     * @return type
     */
    public static function getTemplate($where = [])
    {
        return self::where($where)->first();
    }

    /**
     * Create email template record
     * @param array $request
     * @return type
     */
    protected static function createRecord($request)
    {
        return self::create($request);
    }

    /**
     * Update email template record
     * @param array $request
     * @param type $id
     * @return type
     */
    protected static function updateRecord($request, $id)
    {
        unset($request['q']);
        return self::where(['id' => $id])->update($request);
    }
}
