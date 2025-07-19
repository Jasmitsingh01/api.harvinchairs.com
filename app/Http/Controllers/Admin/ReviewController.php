<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyReviewRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Review;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('review_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Review::with(['order', 'user', 'product', 'product_attributes'])->select(sprintf('%s.*', (new Review)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'review_show';
                $editGate      = 'review_edit';
                $deleteGate    = 'review_delete';
                $crudRoutePart = 'reviews';

                return view('partials.ViewDeleteActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('order_tracking_number', function ($row) {
                return $row->order ? $row->order->tracking_number : '';
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('comment', function ($row) {
                return $row->comment ? $row->comment : '';
            });
            $table->editColumn('rating', function ($row) {
                return $row->rating ? $row->rating : '';
            });

            $table->editColumn('is_active', function ($row) {
                return $row->is_active ?
                '<button class="border-0 text-success bg-transparent btn-active" data-id="' .$row->id.
                '"><i class="fa-solid fa-circle-check"></i></button>' :
                '<button class="border-0 text-danger bg-transparent btn-inactive" data-id="'  .$row->id.
                '"><i class="fa-solid fa-circle-xmark"></i></button>';
            });

            $table->editColumn('customer_name', function ($row) {
                return $row->customer_name ? $row->customer_name : '';
            });
            $table->editColumn('date', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'order', 'user', 'product', 'product_attributes', 'is_active', 'photos']);

            return $table->make(true);
        }
        return view('admin.reviews.index');
    }

    public function create()
    {
        abort_if(Gate::denies('review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('tracking_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()->pluck('full_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_attributes = ProductAttribute::pluck('reference_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.reviews.create', compact('orders', 'product_attributes', 'products', 'users'));
    }

    public function store(StoreReviewRequest $request)
    {
        abort_if(Gate::denies('review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review = Review::create($request->all());

        foreach ($request->input('photos', []) as $file) {
            $review->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $review->id]);
        }

        return redirect()->route('admin.reviews.index');
    }

    public function edit(Review $review)
    {
        abort_if(Gate::denies('review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('tracking_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_attributes = ProductAttribute::pluck('reference_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $review->load('order', 'user', 'product', 'product_attributes');

        return view('admin.reviews.edit', compact('orders', 'product_attributes', 'products', 'review', 'users'));
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        abort_if(Gate::denies('review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review->update($request->all());

        if (count($review->photos) > 0) {
            foreach ($review->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $review->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $review->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        return redirect()->route('admin.reviews.index');
    }

    public function show(Review $review)
    {
        abort_if(Gate::denies('review_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review->load('order', 'user', 'product', 'product_attributes');

        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        abort_if(Gate::denies('review_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $review->delete();

        return back();
    }

    public function massDestroy(MassDestroyReviewRequest $request)
    {
        $reviews = Review::find(request('ids'));

        foreach ($reviews as $review) {
            $review->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('review_create') && Gate::denies('review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Review();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function updateStatus(Request $request)
    {
        abort_if(Gate::denies('review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id' => 'required|exists:reviews,id',
            'field' => 'required|in:is_active',
            'value' => 'required',
        ]);

        $review = Review::find($request->id);
        if (!$review) {
            return response()->json(['error' => 'review not found.'], 404);
        }

        $review->{$request->field} = $request->value;
        $review->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
