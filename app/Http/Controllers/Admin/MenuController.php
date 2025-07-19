<?php

namespace App\Http\Controllers\Admin;
use Session;
use App\Models\Menu;
use App\Models\post;
use App\Models\Category;
use App\Models\Menuitem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\StaticPage;

class MenuController extends Controller
{
    use MediaUploadingTrait;

  public function index(){
    $menuitems = '';
    $desiredMenu = '';
    $allMenus = Menu::all();
    if(isset($_GET['id']) && $_GET['id'] != 'new'){
      $id = $_GET['id'];
      $desiredMenu = Menu::where('id',$id)->first();
      if($desiredMenu->content != ''){
        $menuitems = json_decode($desiredMenu->content);
        $menuitems = $menuitems[0];
        foreach($menuitems as $menu){
            $menu->banner_image = Menuitem::where('id',$menu->id)->value('banner_image');
            if(isset($menu->banner_image)){
                $menu->banner_image = json_decode($menu->banner_image,true);
            }
          $menu->title = Menuitem::where('id',$menu->id)->value('title');
          $menu->name = Menuitem::where('id',$menu->id)->value('name');
          $menu->is_mega_menu = Menuitem::where('id',$menu->id)->value('is_mega_menu');
          $menu->active = Menuitem::where('id',$menu->id)->value('active');
          $menu->color_code = Menuitem::where('id',$menu->id)->value('color_code');
          $menu->text_bold = Menuitem::where('id',$menu->id)->value('text_bold');
          $menu->slug = Menuitem::where('id',$menu->id)->value('slug');
          $menu->target = Menuitem::where('id',$menu->id)->value('target');
          $menu->type = Menuitem::where('id',$menu->id)->value('type');
          $menu->is_external = Menuitem::where('id',$menu->id)->value('is_external');
          if(!empty($menu->children[0])){
            foreach ($menu->children[0] as $child) {
              $child->title = Menuitem::where('id',$child->id)->value('title');
              $child->name = Menuitem::where('id',$child->id)->value('name');
              $child->slug = Menuitem::where('id',$child->id)->value('slug');
              $child->active = Menuitem::where('id',$child->id)->value('active');
              $child->color_code = Menuitem::where('id',$child->id)->value('color_code');
              $child->text_bold = Menuitem::where('id',$child->id)->value('text_bold');
              $child->target = Menuitem::where('id',$child->id)->value('target');
              $child->type = Menuitem::where('id',$child->id)->value('type');
              $child->is_external = Menuitem::where('id',$child->id)->value('is_external');
            }
          }
        }
      }else{
        $menuitems = Menuitem::where('menu_id',$desiredMenu->id)->get();
      }
    }else{
      $desiredMenu = Menu::orderby('id','DESC')->first();
      if($desiredMenu){
        if($desiredMenu->content != ''){
          $menuitems = json_decode($desiredMenu->content);
          $menuitems = $menuitems[0];
          foreach($menuitems as $menu){
            $menu->banner_image = Menuitem::where('id',$menu->id)->value('banner_image');
            if(isset($menu->banner_image)){
                $menu->banner_image = json_decode($menu->banner_image,true);
            }
            $menu->title = Menuitem::where('id',$menu->id)->value('title');
            $menu->name = Menuitem::where('id',$menu->id)->value('name');
            $menu->is_mega_menu = Menuitem::where('id',$menu->id)->value('is_mega_menu');
            $menu->active = Menuitem::where('id',$menu->id)->value('active');
            $menu->color_code = Menuitem::where('id',$menu->id)->value('color_code');
            $menu->text_bold = Menuitem::where('id',$menu->id)->value('text_bold');
            $menu->slug = Menuitem::where('id',$menu->id)->value('slug');
            $menu->target = Menuitem::where('id',$menu->id)->value('target');
            $menu->type = Menuitem::where('id',$menu->id)->value('type');
            $menu->is_external = Menuitem::where('id',$menu->id)->value('is_external');
            if(!empty($menu->children[0])){
              foreach ($menu->children[0] as $child) {
                $child->title = Menuitem::where('id',$child->id)->value('title');
                $child->name = Menuitem::where('id',$child->id)->value('name');
                $child->is_mega_menu = Menuitem::where('id',$child->id)->value('is_mega_menu');
                $child->active = Menuitem::where('id',$child->id)->value('active');
                $child->color_code = Menuitem::where('id',$child->id)->value('color_code');
                $child->text_bold = Menuitem::where('id',$child->id)->value('text_bold');
                $child->slug = Menuitem::where('id',$child->id)->value('slug');
                $child->target = Menuitem::where('id',$child->id)->value('target');
                $child->type = Menuitem::where('id',$child->id)->value('type');
                $child->is_external = Menuitem::where('id',$child->id)->value('is_external');
                if(!empty($child->children[0])){
                    foreach ($child->children[0] as $child2) {
                        $child2->title = Menuitem::where('id',$child2->id)->value('title');
                        $child2->name = Menuitem::where('id',$child2->id)->value('name');
                        $child2->is_mega_menu = Menuitem::where('id',$child2->id)->value('is_mega_menu');
                        $child2->active = Menuitem::where('id',$child2->id)->value('active');
                        $child2->color_code =Menuitem::where('id',$child2->id)->value('color_code');
                        $child2->text_bold = Menuitem::where('id',$child2->id)->value('text_bold');
                        $child2->slug = Menuitem::where('id',$child2->id)->value('slug');
                        $child2->target = Menuitem::where('id',$child2->id)->value('target');
                        $child2->type = Menuitem::where('id',$child2->id)->value('type');
                        $child2->is_external = Menuitem::where('id',$child2->id)->value('is_external');
                    }
                }
              }
            }
          }
        }else{
          $menuitems = Menuitem::where('menu_id',$desiredMenu->id)->get();
        }
      }

    }


    if(isset($_GET['create']) && $_GET['create'] == 'new'){
        $desiredMenu = '';
    }
    $categories = Category::where('enabled', true)->where('language', 'en')->get();
    $staticPages = StaticPage::get();

    return view ('admin.menus.mega-menu-create',['categories'=>$categories,'posts'=>[],'menus'=>menu::all(),'desiredMenu'=>$desiredMenu,'menuitems'=>$menuitems,'staticPages' => $staticPages,'allMenus' => $allMenus]);
  }

  public function store(Request $request){
	$data = $request->all();
	if(Menu::create($data)){
	  $newdata = Menu::orderby('id','DESC')->first();
	  session::flash('success','Menu saved successfully !');
	  return redirect("admin/menus?id=$newdata->id");
	}else{
	  return redirect()->back()->with('error','Failed to save menu !');
	}
  }

  public function addCatToMenu(Request $request){
    $data = $request->all();
    $menuid = $request->menuid;
    $ids = $request->ids;
    $menu = Menu::findOrFail($menuid);
    if($menu->content == ''){
      foreach($ids as $id){
        $data['title'] = Category::where('id',$id)->value('name');
        $data['slug'] = Category::where('id',$id)->value('slug');
        $data['type'] = 'category';
        $data['category_id'] = $id;
        $data['menu_id'] = $menuid;
        $data['updated_at'] = NULL;
        Menuitem::create($data);
      }
    }else{
      $olddata = json_decode($menu->content,true);
      foreach($ids as $id){
        $data['title'] = Category::where('id',$id)->value('name');
        $data['slug'] = Category::where('id',$id)->value('slug');
        $data['category_id'] = $id;
        $data['type'] = 'category';
        $data['menu_id'] = $menuid;
        $data['updated_at'] = NULL;
        Menuitem::create($data);
      }
      foreach($ids as $id){
        $array['title'] = Category::where('id',$id)->value('name');
        $array['slug'] = Category::where('id',$id)->value('slug');
        $array['name'] = NULL;
        $array['type'] = 'category';
        $data['category_id'] = $id;
        $array['target'] = NULL;
        $array['id'] = Menuitem::where('slug',$array['slug'])->where('name',$array['name'])->where('type',$array['type'])->value('id');
        $array['children'] = [[]];
        array_push($olddata[0],$array);
        $oldata = json_encode($olddata);
        $menu->update(['content'=>$olddata]);
      }
    }
  }

  public function addPostToMenu(Request $request){
    $data = $request->all();
    $menuid = $request->menuid;
    $ids = $request->ids;
    $menu = Menu::findOrFail($menuid);
    if($menu->content == ''){
      foreach($ids as $id){
        $data['title'] = StaticPage::where('id',$id)->value('name');
        $data['slug'] = StaticPage::where('id',$id)->value('slug');
        $data['type'] = 'post';
        $data['menu_id'] = $menuid;
        $data['updated_at'] = NULL;
        menuitem::create($data);
      }
    }else{
      $olddata = json_decode($menu->content,true);
      foreach($ids as $id){
        $data['title'] = StaticPage::where('id',$id)->value('name');
        $data['slug'] = StaticPage::where('id',$id)->value('slug');
        $data['type'] = 'post';
        $data['menu_id'] = $menuid;
        $data['updated_at'] = NULL;
        menuitem::create($data);
      }
      foreach($ids as $id){
        $array['title'] = StaticPage::where('id',$id)->value('name');
        $array['slug'] = StaticPage::where('id',$id)->value('slug');
        $array['name'] = NULL;
        $array['type'] = 'post';
        $array['target'] = NULL;
        $array['id'] = menuitem::where('slug',$array['slug'])->where('name',$array['name'])->where('type',$array['type'])->orderby('id','DESC')->value('id');
        $array['children'] = [[]];
        array_push($olddata[0],$array);
        $oldata = json_encode($olddata);
        $menu->update(['content'=>$olddata]);
      }
    }
  }

  public function addCustomLink(Request $request){
    $data = $request->all();
    $menuid = $request->menuid;
    $menu = Menu::findOrFail($menuid);
    if($menu->content == ''){
      $data['title'] = $request->link;
      $data['slug'] = $request->url;
      $data['type'] = 'custom';
      $data['menu_id'] = $menuid;
      $data['updated_at'] = NULL;
      $data['is_external'] = $request->is_external;
      Menuitem::create($data);
    }else{
      $olddata = json_decode($menu->content,true);
      $data['title'] = $request->link;
      $data['slug'] = $request->url;
      $data['type'] = 'custom';
      $data['menu_id'] = $menuid;
      $data['updated_at'] = NULL;
      $data['is_external'] = $request->is_external;
      Menuitem::create($data);
      $array = [];
      $array['title'] = $request->link;
      $array['slug'] = $request->url;
      $array['name'] = NULL;
      $array['type'] = 'custom';
      $array['target'] = NULL;
      $data['is_external'] = $request->is_external;
      $array['id'] = Menuitem::where('slug',$array['slug'])->where('name',$array['name'])->where('type',$array['type'])->orderby('id','DESC')->value('id');
      $array['children'] = [[]];
      array_push($olddata[0],$array);
      $oldata = json_encode($olddata);
      $menu->update(['content'=>$olddata]);
    }
  }

  public function updateMenu(Request $request){
    $newdata = $request->all();
    $menu=Menu::findOrFail($request->menuid);

    if ($request->input('banner', false)) {

        $menu->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->preservingOriginal()->toMediaCollection('banner');
        $converted_url = [
            'thumbnail' => $menu->banners->getUrl('thumb'),
            'original' => $menu->banners->getUrl(),
            'filepath' =>$menu->banners->getPath(),
        ];

        $menu->update(['banner'=>json_encode($converted_url)]);
    }
    $menu=Menu::findOrFail($request->menuid);
    $content = $request->data;
    $newdata = [];
    $newdata['location'] = $request->location;
    $newdata['content'] = json_encode($content);
    $menu->update($newdata);
  }

  public function updateMenuItem(Request $request){
    $data = $request->all();
    $item = Menuitem::findOrFail($request->id);
    if ($request->input('banner', false)) {

        $item->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->preservingOriginal()->toMediaCollection('banner');
        $converted_url = [
            'thumbnail' => $item->banner->getUrl('thumb'),
            'original' => $item->banner->getUrl(),
            'filepath' =>$item->banner->getPath(),
        ];
       $data['banner_image'] = json_encode($converted_url);
    }
    if(!isset($data['is_external'])){
        $data['is_external'] = 0;
    }
    // $item = menuitem::findOrFail($request->id);
    $item->update($data);
    return redirect()->back();
  }

  public function deleteMenuItem($id,$key,$in='',$in2=''){
    $menuitem = Menuitem::findOrFail($id);

    $menu = Menu::where('id',$menuitem->menu_id)->first();

    if($menu->content != ''){
      $data = json_decode($menu->content,true);
      $maindata = $data[0];
      if($in == ''){
        // dd($data[0][$key]);
        unset($data[0][$key]);
        $newdata = json_encode($data);
        $menu->update(['content'=>$newdata]);
      }elseif($in2 == '' && $in != '' ){
        unset($data[0][$key]['children'][0][$in]);
        $newdata = json_encode($data);
        $menu->update(['content'=>$newdata]);
      }else{
        unset($data[0][$key]['children'][0][$in]['children'][$in2]);

	    $newdata = json_encode($data);
        $menu->update(['content'=>$newdata]);
      }
    }
    $menuitem->delete();
    return redirect()->back();
  }

  public function destroy(Request $request){
    Menuitem::where('menu_id',$request->id)->delete();
    Menu::findOrFail($request->id)->delete();
    return redirect('manage-menus')->with('success','Menu deleted successfully');
  }
}
