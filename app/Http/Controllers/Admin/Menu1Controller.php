<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMenuRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMenuRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Category;
use App\Models\Menu;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Menu::with(['categories'])->select(sprintf('%s.*', (new Menu)->table));
            // dd(  $query);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate      = 'menu_show';
                $editGate      = 'menu_edit';
                $deleteGate    = 'menu_delete';
                $crudRoutePart = 'menus';

                return view('partials.Actions', compact(
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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('categories', function ($row) {
                $labels = $row->categories->count();
                // foreach ($row->categories as $category) {
                //     $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $category->name);
                // }

                return $labels;
            });
            $table->editColumn('parent_id', function ($row) {
                return $row->parent_id ? $row->parent_id : '';
            });
            $table->editColumn('position', function ($row) {
                return $row->position ? $row->position : 0;
            });

            $table->rawColumns(['actions', 'placeholder', 'categories', 'active']);

            return $table->make(true);
        }

        $categories = Category::where('enabled', true)->where('language', 'en')->get();

        return view('admin.menus.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::where('enabled', true)->where('language', 'en')->pluck('name', 'id');
        $all_menus = Menu::where('active', true)->pluck('name', 'id');
        return view('admin.menus.create', compact('categories','all_menus'));
    }

    public function store(StoreMenuRequest $request)
    {
        $menu = Menu::create($request->all());
        if($request->is_cms == false){
            $menu->categories()->sync($request->input('categories', []));
        }
        if ($request->input('image', false)) {

            $menu->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->preservingOriginal()->toMediaCollection('image');
            $converted_url = [
                'thumbnail' => $menu->image->getUrl('thumb'),
                'original' => $menu->image->getUrl(),
                'filepath' =>$menu->image->getPath(),
            ];
            $menu->update(['images'=>json_encode($converted_url)]);
        }
        return redirect()->route('admin.menus.index');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::where('enabled', true)->where('language', 'en')->pluck('name', 'id');
        $all_menus = Menu::where('active', true)->pluck('name', 'id');
        $menu->load('categories');

        return view('admin.menus.edit', compact('categories', 'menu', 'all_menus'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->all());
        $menu->categories()->sync($request->input('categories', []));

        if ($request->input('image', false)) {
            if (! $menu->image || $request->input('image') !== $menu->image->file_name) {
                if ($menu->image) {
                    $menu->image->delete();
                }
                $newImage=   $menu->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');

                $newImage->url = $newImage->getUrl();
                $newImage->thumbnail = $newImage->getUrl('thumb');
                $newImage->filepath = $newImage->getPath();

                $converted_url = [
                    'thumbnail' =>  $newImage->thumbnail,
                    'original' =>    $newImage->url,
                    'filepath' =>    $newImage->filepath,
                ];
                $menu->update(['images'=>json_encode($converted_url)]);
            }
        }elseif ($menu->image) {
            $menu->image->delete();
            $menu->update(['images'=>null]);
        }

        return redirect()->route('admin.menus.index');
    }

    public function show(Menu $menu)
    {
        $menu->load('categories');
        return view('admin.menus.show', compact('menu'));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back();
    }

    public function massDestroy(MassDestroyMenuRequest $request)
    {
        $menus = Menu::find(request('ids'));

        foreach ($menus as $menu) {
            $menu->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function reorder(Request $request)
    {
        foreach($request->input('rows', []) as $row)
        {
            Menu::find($row['id'])->update([
                'position' => $row['position']
            ]);
        }

        return response()->noContent();
    }
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:menus,id',
            'field' => 'required|in:active',
            'value' => 'required|boolean',
        ]);

        $menu = Menu::find($request->id);

        if (!$menu) {
            return response()->json(['error' => 'Menu not found.'], 404);
        }

        $menu->{$request->field} = $request->value;
        $menu->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
