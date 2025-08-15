<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTestimonialRequest;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Models\Testimonial;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;

class TestimonialController extends Controller
{
    use MediaUploadingTrait;
    public function index()
    {

        $testimonials = Testimonial::with(['customer'])->get();
        $users = User::get();

        return view('admin.testimonials.index', compact('testimonials', 'users'));
    }

    public function create()
    {

        $customers = User::pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.testimonials.create', compact('customers'));
    }

    public function store(StoreTestimonialRequest $request)
    {
        $testimonial = Testimonial::create($request->all());

        if ($request->input('author_image', false)) {
            $testimonial->addMedia(storage_path('tmp/uploads/' . basename($request->input('author_image'))))->preservingOriginal()->toMediaCollection('author_image');
        }

        return redirect()->route('admin.testimonials.index');
    }

    public function edit(Testimonial $testimonial)
    {

        $customers = User::pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $testimonial->load('customer');

        return view('admin.testimonials.edit', compact('customers', 'testimonial'));
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $testimonial->update($request->all());

        if ($request->input('author_image', false)) {
            if (! $testimonial->author_image || $request->input('author_image') !== $testimonial->author_image->file_name) {
                if ($testimonial->author_image) {
                    $testimonial->author_image->delete();
                }
                $testimonial->addMedia(storage_path('tmp/uploads/' . basename($request->input('author_image'))))->toMediaCollection('author_image');
            }
        }

        return redirect()->route('admin.testimonials.index');
    }

    public function show(Testimonial $testimonial)
    {

        $testimonial->load('customer');

        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function destroy(Testimonial $testimonial)
    {

        $testimonial->delete();

        return back();
    }

    public function massDestroy(MassDestroyTestimonialRequest $request)
    {
        $testimonials = Testimonial::find(request('ids'));

        foreach ($testimonials as $testimonial) {
            $testimonial->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function updateStatus(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:testimonials,id',
            'field' => 'required|in:active',
            'value' => 'required|boolean',
        ]);

        $testimonial = Testimonial::find($request->id);

        if (!$testimonial) {
            return response()->json(['error' => 'Testimonials not found.'], 404);
        }

        $testimonial->{$request->field} = $request->value;
        $testimonial->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
