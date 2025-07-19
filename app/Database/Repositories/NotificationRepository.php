<?php


namespace App\Database\Repositories;

use App\Models\Notification;

class NotificationRepository extends BaseRepository
{
    protected $dataArray = [
        'title',
        'description'
    ];
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }
     /**
     * @param $request
     * @return LengthAwarePaginator|JsonResponse|Collection|mixed
     */
    public function storeNotification($request)
    {
        try {
            $notificationInput = $request->only($this->dataArray);
            return $this->create($notificationInput);
        } catch (Exception $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
