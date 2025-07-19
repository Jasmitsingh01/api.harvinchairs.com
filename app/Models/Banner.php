<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Banner extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'banners';
    protected $hidden = ['media','link'];

    protected $appends = [
        'link_url',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    // protected $casts = [
    //     'banner'   => 'json',
    // ];
    protected $fillable = [
        'type',
        'title',
        'dis_index',
        'category_id',
        'active',
        'banner',
        'link',
        'link_open',
        'display_text',
        'text_colour',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public const TYPE_SELECT = [
        'Home Banner 1'        => 'Home Banner 1',
        // 'Home Banner 2'        => 'Home Banner 2',
        'Home Banner 3'        => 'Home Banner 3',
        // 'Home Banner 4'        => 'Home Banner 4',
        // 'Side Banner'          => 'Side Banner',
        /*'Creative Cuts Banner' => 'Creative Cuts Banner',*/
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    // public function getBannerAttribute($value)
    // {

    //     // if(config('app.env') == "local"){
    //     //     $file = $this->getMedia('banner')->last();
    //     //     if ($file) {
    //     //         $file->url       = $file->getUrl();
    //     //         $file->thumbnail = $file->getUrl('thumb');
    //     //         $file->preview   = $file->getUrl('preview');
    //     //     }
    //     //     return $file;
    //     // }
    //     // else{
    //         // return $value;
    //         return json_decode($value,true);
    //     // }

    // }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function getBannersAttribute()
    {
        $file = $this->getMedia('banner')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }
            return $file;

    }

    public function getBannerAttribute($value){
        $file = $this->getMedia('banner')->last();

        $urls = new \stdClass();

        if ($file) {
            $urls->url       = $file->getUrl();
            $urls->thumbnail = $file->getUrl('thumb');
            $urls->preview   = $file->getUrl('preview');
        }

        return $urls;
    }

    public function getLinkUrlAttribute(){
        return str_replace('[WEB_APP_URL]', config('constants.WEB_APP_URL'), $this->link);
    }
}
