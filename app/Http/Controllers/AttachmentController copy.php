<?php


namespace App\Http\Controllers;
use File;
use ImageKit\ImageKit;
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
        // $imageKit = new ImageKit(
        //     "public_9G30tjBUUNldm/jWJzymsPdUSOw=",
        //     "private_1zyhj/TnB4+1yAKAbdrjzHf9+uU=",
        //     "https://ik.imagekit.io/iydrusetp"
        // );
        // // Upload the file to ImageKit
        // $uploadFile = $imageKit->uploadFile([
        //     "file" => fopen($media->getPath(),"r"),
        //     "fileName" => "my_file_name.jpg",
        //     "tags" => ["tag1", "tag2"]
        // ]);
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

                $imageKit = new ImageKit(
                    "public_9G30tjBUUNldm/jWJzymsPdUSOw=",
                    "private_1zyhj/TnB4+1yAKAbdrjzHf9+uU=",
                    "https://ik.imagekit.io/iydrusetp"
                );
                // Upload the file to ImageKit
                $uploadFile = $imageKit->uploadFile([
                    "file" => fopen($media->getPath(),"r"),
                    "fileName" => "my_file_name.jpg",
                    "tags" => ["tag1", "tag2"]
                ]);
                // $uploadedFile = $imageKit->upload([
                //     'file' => $media->getPath(),
                //     'fileName' => $media->file_name,
                //     'folder' => '/uploads' // Specify the folder where you want to store the file
                // ]);

                $data = json_encode($uploadFile);
                // dd($uploadFile->result->thumbnailUrl);
                // return response()->json($uploadFile);
                if (strpos($media->mime_type, 'image/') !== false) {
                    $converted_url = [
                        'thumbnail' =>$uploadFile->result->thumbnailUrl,
                        'original' => $uploadFile->result->url,
                        'id' => $attachment->id
                    ];
                } else {
                    $converted_url = [
                        'thumbnail' => '',
                        'original' => $uploadFile->result->url,
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
