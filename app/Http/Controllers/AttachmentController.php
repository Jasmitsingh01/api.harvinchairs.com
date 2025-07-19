<?php


namespace App\Http\Controllers;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Database\Models\Attachment;
use App\Exceptions\MarvelException;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AttachmentRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Database\Repositories\AttachmentRepository;
use Prettus\Validator\Exceptions\ValidatorException;


class AttachmentController extends CoreController
{
    public $repository;

    public function __construct(AttachmentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Attachment[]
     */
    public function index(Request $request)
    {
        return $this->repository->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AttachmentRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(AttachmentRequest $request)
    {
        $urls = [];
        foreach ($request->attachment as $media) {
            $attachment = new Attachment;
            $attachment->save();

            // $photo = $media;
            // $imagename = $media->getClientOriginalName();
            // /* thumb image upload */
            // $destinationPath = public_path('storage/'.$attachment->id.'/conversations');
            // if(!File::isDirectory($destinationPath)){
            //     File::makeDirectory($destinationPath, 0777, true, true);
            // }

            // $img =  Image::make($photo->path());
            // // dd($img);

            // $img->resize(368, 232, function ($constraint) {
            //     $constraint->aspectRatio();
            // });

            // $path = Storage::disk('public')->put($attachment->id.'/conversations/'.$imagename, $img->stream());
            // /* thumb image upload */
            // /* original image upload*/
            // Storage::disk('public')->putFileAs($attachment->id.'/', $photo,$imagename);
            // /* original image upload*/
            // // dd($attachment->id);
            // // $attachment->thumbnail = $imagename;
            // $converted_url = [
            //     'thumbnail' => config('constants.FILESYSTEM_PATH').'/storage/'.$attachment->id.'/conversations/'.$imagename,
            //     'original' => config('constants.FILESYSTEM_PATH').'/storage/'.$attachment->id.'/'.$imagename,
            //     'id' => $attachment->id
            // ];

            // $attachment->addMedia($media)->toMediaCollection('images');
            $attachment->addMedia($media)->preservingOriginal()->toMediaCollection();
            foreach ($attachment->getMedia() as $media) {
                if (strpos($media->mime_type, 'image/') !== false) {
                    $converted_url = [
                        'thumbnail' => $media->getUrl('thumbnail'),
                        'original' => $media->getUrl(),
                        'filepath' =>$media->getPath(),
                        'id' => $attachment->id
                    ];
                } else {
                    $converted_url = [
                        'thumbnail' => '',
                        'original' => $media->getUrl(),
                        'filepath' => $media->getPath(),
                        'id' => $attachment->id
                    ];
                }
            }
            $urls[] = $converted_url;
        }
        return $urls;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $this->repository->findOrFail($id);
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AttachmentRequest $request
     * @param int $id
     * @return bool
     */
    public function update(AttachmentRequest $request, $id)
    {
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new MarvelException(NOT_FOUND);
        }
    }
}
