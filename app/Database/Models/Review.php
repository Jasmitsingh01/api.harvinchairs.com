<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Review extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;

    protected $table = 'reviews';

    public $guarded = [];

    protected $casts = [
        'photos' => 'json',
    ];

    protected $appends = [
       // 'positive_feedbacks_count',
       // 'negative_feedbacks_count',
       // 'my_feedback',
       // 'abusive_reports_count'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'shop_id',
        'old_id'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class, 'product_id');
    }

    /**
     * @return belongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the reviews feedbacks.
     */
    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'model');
    }
    public function abusive_reports()
    {
        return $this->morphMany(AbusiveReport::class, 'model');
    }

    /**
     * Positive feedback count of review .
     * @return int
     */
    public function getPositiveFeedbacksCountAttribute()
    {
        return $this->feedbacks()->wherePositive(1)->count();
    }

    /**
     * Negative feedback count of review .
     * @return int
     */
    public function getNegativeFeedbacksCountAttribute()
    {
        return $this->feedbacks()->whereNegative(1)->count();
    }

    /**
     * Get authenticated user feedback
     * @return object | null
     */
    public function getMyFeedbackAttribute()
    {
        if(auth()->guard('api')->user()) {
            return $this->feedbacks()->where('user_id', auth()->guard('api')->user()->id)->first();
        }
        return null;
    }

    /**
     * Count no of abusive reports in the review.
     * @return int
     */
    public function getAbusiveReportsCountAttribute() {
        return $this->abusive_reports()->count();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

}
