<?php

namespace App\Http\Controllers;

use App\Database\Models\Category;
use Illuminate\Http\Request;
use App\Database\Repositories\MenuRepository;
use App\Models\Menu;
use App\Models\Menuitem;

class MenuController extends Controller
{
    public $repository;

    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $language = $request->language ??  config('shop.default_language');
        $limit = $request->limit ? $request->limit : 15;
        $menus = $this->repository->where('active', true)->with('categories')->orderBy('position', 'asc')->paginate($limit);
        $processedMenus = [];

        foreach ($menus as $menu) {
            $processedCategories = [];
            foreach ($menu->categories as $category) {
                if ($category->parent != config('shop.parent_id')) {
                    // This is a child category, find its parent and add it there
                    $parentIndex = array_search($category->parent, array_column($processedCategories, 'id'));

                    if ($parentIndex !== false) {
                        if (!isset($processedCategories[$parentIndex]['children'])) {
                            $processedCategories[$parentIndex]['children'] = [];
                        }
                        $processedCategories[$parentIndex]['children'][] = $category->toArray();
                    } else {
                        // Parent category not found, create a new parent category entry
                        $newParentCategory = $category->toArray();
                    }
                } else {
                    // This is a parent category, add it to the processedCategories array
                    $processedCategories[] = array_merge($category->toArray(), [
                        'children' => $category->children,
                    ]);
                }
            }

            $processedMenu = $menu->toArray();
            $processedMenu['categories'] = $processedCategories;
            $processedMenus[] = $processedMenu;
        }

        foreach ($processedMenus as $key => $value) {
            // Check if the menu has the "is_category" flag set to true
            if ($value['is_category'] == true) {
                $mainCategories = [];
                foreach ($value['categories'] as $category) {
                    // Flatten the nested "children" array
                    $mainCategories = array_merge($mainCategories, $category['children']);
                }
                $processedMenus[$key]['categories'] = $mainCategories;
            }
        }

        return $processedMenus;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getMenus(Request $request){
        if(isset($request->id) && $request->id != '')
        {
            $desiredMenu = Menu::where('id',$request->id)->first();
        }else{
            $desiredMenu = Menu::orderby('id','ASC')->first();
        }
        if($desiredMenu){
          if($desiredMenu->content != ''){
            $menuitems = json_decode($desiredMenu->content);
            $menuitems = $menuitems[0];
            $i=0;
            $menuArr = [];
            foreach($menuitems as $menu){
                $menuItem = Menuitem::where('id',$menu->id)->first();
                if ($menuItem === null) {
                    continue;
                }
                if($menuItem->active == 0){
                    unset($menu);
                    continue;
                }
                // check added menu is category and is already deleted
                if($menuItem->category_id > 0){
                    // check this category_id is exist in category table
                    if(!Category::where('id',$menuItem->category_id)->exists()){
                        unset($menu);
                        continue;
                    }
                }
                //$menuArr[$i]['id'] = $menuItem->id;
                $menuArr[$i]['banner_image'] = $menuItem->banner_image;
                if(isset($menuArr[$i]['banner_image'])){
                    $menuArr[$i]['banner_image'] = json_decode($menuArr[$i]['banner_image'],true);
                }
                $menuArr[$i]['title'] = (!empty($menuItem->name)) ? $menuItem->name : $menuItem->title;
                //$menu->is_mega_menu = Menuitem::where('id',$menu->id)->value('is_mega_menu');
                //$menuArr[$i]['category_id'] = $menuItem->category_id;
                //$menu->active = $menuItem->active;
                $menuArr[$i]['color_code'] = $menuItem->color_code;
                $menuArr[$i]['is_bold'] = $menuItem->text_bold;
                $menuArr[$i]['url'] = $menuItem->url;
                $menuArr[$i]['target'] = (!empty($menuItem->target)) ? $menuItem->target : "_self";
                $menuArr[$i]['is_external'] = $menuItem->is_external;
                //dd($menu);
                //$menuArr[$i] = get_object_vars($menu);
                $j=0;
                if(!empty($menu->children[0])){
                  foreach ($menu->children[0] as $child) {
                    $childmenu = Menuitem::where('id',$child->id)->first();
                    if(!isset($childmenu)){
                        unset($child);
                        continue;
                    }
                    if($childmenu->active == 0){
                        unset($child);
                        continue;
                    }
                    if($childmenu->cateogry_id > 0){
                        // check this category_id is exist in category table
                        if(!Category::where('id',$childmenu->category_id)->exists()){
                            unset($child);
                            continue;
                        }
                    }
                    $menuArr[$i]['submenu'][$j]['title'] = (!empty($childmenu->name)) ? $childmenu->name : $childmenu->title;
                    //$child->name = $childmenu->name;
                    //$menuArr[$i]['submenu']['category_id'] = $childmenu->category_id;
                    //$child->is_mega_menu = Menuitem::where('id',$child->id)->value('is_mega_menu');
                    //$child->active = Menuitem::where('id',$child->id)->value('active');
                    //dd($childmenu->category);
                    $menuArr[$i]['submenu'][$j]['color_code'] = $childmenu->color_code;
                    $menuArr[$i]['submenu'][$j]['is_bold'] = $childmenu->text_bold;
                    $menuArr[$i]['submenu'][$j]['url'] = $childmenu->url;
                    $menuArr[$i]['submenu'][$j]['target'] = $childmenu->target;
                    //$menuArr[$i]['submenu']['type'] = $childmenu->type;
                    $menuArr[$i]['submenu'][$j]['is_external'] = $childmenu->is_external;
                    if(!empty($child->children[0])){
                        foreach ($child->children[0] as $child2) {
                            $child2menu = Menuitem::where('id',$child2->id)->first();
                            if($child2menu->active == 0){
                                unset($child2);
                                continue;
                            }
                            $menuArr[$i]['submenu2']['title'] = (!empty($child2menu->name)) ? $child2menu->name : $child2menu->title;
                            //$menuArr[$i]['submenu2']['name'] = $child2menu->name;
                            //$menuArr[$i]['submenu2']['category_id'] = $child2menu->category_id;
                            //$menuArr[$i]['submenu2']['is_mega_menu'] = $child2menu->is_mega_menu;
                            //$child2->active = $child2menu->active');
                            $menuArr[$i]['submenu2']['color_code'] = $child2menu->color_code;
                            $menuArr[$i]['submenu2']['is_bold'] = $child2menu->text_bold;
                            $menuArr[$i]['submenu2']['url'] = $child2menu->url;
                            $menuArr[$i]['submenu2']['target'] = $child2menu->target;
                            //$menuArr[$i]['submenu2']['type'] = $child2menu->type;
                            $menuArr[$i]['submenu2']['is_external'] = $childmenu->is_external;
                           // $menuArr[$i]['submenu2'] = $child2;
                        }
                    }
                    $j++;
                    //$menuArr[$i]['submenu'] = get_object_vars($child);
                  }
                }
                $i++;
              }
          }
        //   else{
        //     $menuitems = Menuitem::where('menu_id',$desiredMenu->id)->get();
        //   }
        }
        return $menuArr;
    }
}
