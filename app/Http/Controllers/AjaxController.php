<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use TCG\Voyager\Models\Page;
use TCG\Voyager\Models\Menu;
use App\Models\wins;
use App\Models\buttons;
use App\Models\inputs;
use App\Models\menu_items;
use App\Models\pet_backgrounds;


class AjaxController extends Controller {


  public $url = 'http://localhost:8000';
  public $path_root = '/storage/';

//----------------------------------------------------------------
//                  Animal & Gender
//----------------------------------------------------------------
  public function index() {

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
      
// берем животных    
    $animals = DB::table('animals')->select('id','animal_title', 'image', 'active')->get();
    for($i = 0; $i<count($animals); $i++){
      $animals[$i]->image = $this->url . $this->path_root . $animals[$i]->image;
    }

// берем гендеры
    $genders = DB::table('genders')->select('id','gender_title', 'gender_image')->get();
    for($i = 0; $i<count($genders); $i++){
      $genders[$i]->gender_image = $this->url . $this->path_root . $genders[$i]->gender_image;
    }

// берем пункты меню
    $my_menu = menu_items::select('id', 'title', 'icon_class', 'url', 'parent_id', 'order')->where('menu_id', 2)->orderBy('order')->get();



// берем страницы
    $pages = DB::table('pages')->select('id', 'title', 'excerpt', 'body', 'image', 'slug', 'status')->get();

// social networks

    $soc_networks = DB::table('social_networks')->select('id', 'name', 'link', 'image', 'active')->get();

// languages

    $languages = DB::table('languages')->select('id', 'title', 'locale', 'active')->get();

// wins

    $wins = DB::table('wins')->select('id', 'title')->get();

// buttons

    $buttons = buttons::get();

// inputs

    $inputs = inputs::get();


    $animals_genders['animals'] = $animals;
    $animals_genders['genders'] = $genders;
    $animals_genders['menu'] = $my_menu;
    $animals_genders['pages'] = $pages;
    $animals_genders['soc_networks'] = $soc_networks;
    $animals_genders['languages'] = $languages;
    $animals_genders['wins'] = $wins;
    $animals_genders['buttons'] = $buttons;
    $animals_genders['inputs'] = $inputs;

    
    return response()->json(array('animals_genders'=> $animals_genders), 200);
  }


//----------------------------------------------------------------
//                  Languages
//----------------------------------------------------------------
// public function get_lang() {
//   header('Access-Control-Allow-Origin: *');
//   header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
//   header('Access-Control-Allow-Headers: Content-Type');

//   $locale = $_GET['locale'];

//    $my_menu = menu('application_menu', '_json')->translate($locale);
//     // $my_menu = dd(menu('application_menu'));
//      $pages = Page::get()->translate($locale);
//      $wins = wins::get()->translate($locale);

     
    

//   $translate['pages'] = $pages;
//   $translate['wins'] = $wins;
//    $translate['my_menu'] = $my_menu;

  

//   return response()->json(array('translate'=> $translate), 200);

// }



public function get_lang(Request $request)
{ 

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
  header('Access-Control-Allow-Headers: Content-Type');

 $locale = $request->locale ? $request->locale : 'en';
 
 return response()->json(
  array('translate' => array(
    'pages' => Page::get()->translate($locale),
    'wins' => wins::get()->translate($locale),
    //  'my_menu' => dd(menu('application_menu', '_json')->translate($locale))
    //  'my_menu' => menu('application_menu', '_json')->translate($locale)
     'my_menu' => menu_items::select('id', 'title', 'icon_class', 'url', 'parent_id', 'order')
                            ->where('menu_id', 2)
                            ->orderBy('order')
                            ->get()
                            ->translate($locale),
    'buttons' => buttons::get()->translate($locale),
    'inputs' => inputs::get()->translate($locale)
   
  )),
 200);


}


//----------------------------------------------------------------
//                  Constructor page
//----------------------------------------------------------------

  public function constructor_page() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
    header('Access-Control-Allow-Headers: Content-Type');

    $anim_id = $_GET['anim_id'];
    $gender_id = $_GET['gender_id'];

    // категории данного животного

      // id + hex цветов
      $colors_id_select1 = DB::table('colors')
      ->select('id as color_id', 'rgb_color as bg_color_default');

      $colors_id_select2 = DB::table('colors')
      ->select('id as color_id', 'rgb_color as spec_color_default');


      $categ_parts = DB::table('part_categories')->where('animal_id', '=' , $anim_id)
      ->joinSub($colors_id_select1, 'colors_id_select1', function ($join1) {
      $join1->on('colors_id_select1.color_id', '=', 'part_categories.bg_color_default_id')
      ;})
      ->joinSub($colors_id_select2, 'colors_id_select2', function ($join2) {
      $join2->on('colors_id_select2.color_id', '=', 'part_categories.spec_color_default_id')
      ;
      })->get();




    // $categ_parts = DB::table('part_categories')
    //     ->where('animal_id', $anim_id)
    //     ->orderBy('id')->get();



    // id + changeable категорий

    $categ_id_select = DB::table('part_categories')
        ->select('id as categ_id', 'changeable', 'subjoin', 'required_detail', 'categ_title', 'have_body_color', 'bg_color_default_id', 'spec_color_default_id', 'z_index')
        ->where('animal_id', '=' , $anim_id);

    // выберем id всех запчастей, где есть текущий гендер:
    $parts_id = DB::table('pivot_parts_gender')
    ->select('part_id')
    ->where('gender_id', '=' , $gender_id);

    // соединяем таблицу id запчастей нужного гендера с таблицей запчастей

    $parts_temp = DB::table('parts')
    ->joinSub($parts_id, 'parts_id', function ($join) {
    $join->on('parts_id.part_id', '=', 'parts.id');
    });

    // $parts_TEST = DB::table('parts')
    // ->joinSub($parts_id, 'parts_id', function ($join) {
    // $join->on('parts_id.part_id', '=', 'parts.id');
    // })->get();

     $parts_temp2 = DB::table($parts_temp) 
     ->select('active', 'default', 'id', 'layer_1_code_bg', 'layer_2_code_outline', 'layer_2_code_white', 'layer_3_code_speccol', 'layer_4_code_glare', 'locked', 'part_categ_id', 'part_name');                   


    // запчасти + id категории данного животного

    $parts1 = DB::table($parts_temp2, 'parts')
        ->joinSub($categ_id_select, 'categ_id_select', function ($join) {
        $join->on('categ_id_select.categ_id', '=', 'parts.part_categ_id');
        });


    // сделаем короткую таблицу цветов:
    $short_colors1 = DB::table('colors')
    ->select('id AS color_id', 'rgb_color AS bg_color_default');

    $short_colors2 = DB::table('colors')
    ->select('id AS color_id', 'rgb_color AS spec_color_default');


    
    
    // присоединяем цвета 1

                          $parts_temp_and_color1 = DB::table($parts1, 'parts')
                          ->joinSub($short_colors1, 'color', function ($join) {
                            $join->on('color.color_id', '=', 'parts.bg_color_default_id');
                            });

                          // присоединяем цвета 2

                          $parts = DB::table($parts_temp_and_color1, 'parts')
                          ->joinSub($short_colors2, 'color', function ($join) {
                            $join->on('color.color_id', '=', 'parts.spec_color_default_id');
                            })->get();


                            // $parts = DB::table($parts2)
                            // ->select('active', 'bg_color_default', 'categ_id', 'categ_title', 'changeable', 'default', 'have_body_color', 'id', 'layer_1_code_bg', 'layer_2_code_outline', 'layer_2_code_white', 'layer_3_code_speccol', 'layer_4_code_glare', 'locked', 'part_categ_id', 'part_name', 'spec_color_default', 'subjoin')->get();


//=============================================================================

    // иконка общего цвета

    $color_icons[0] = $this->url . $this->path_root .  setting('site.main_color_icon');
    $color_icons[1] = $this->url . $this->path_root .  setting('site.color_1_icon');
    $color_icons[2] = $this->url . $this->path_root .  setting('site.color_2_icon');


    // группы цвета
    $colors_hues = DB::table('colors_hues')
    ->select('id', 'hue_title', 'hue_hex', 'active')->get();

    // яркости
    $colors_luminosities = DB::table('colors_luminosities')
    ->select('id', 'luminosity_title', 'luminosity_hex', 'active')->get();

    // цвета
    $colors = DB::table('colors')
    ->select('id', 'rgb_color', 'active', 'locked', 'hue_id', 'luminosity_id')->orderBy('rgb_color')->get();





    //==========================================
    $constructor_page['parts'] = $parts;
    $constructor_page['categ_parts'] = $categ_parts;
    $constructor_page['colors_hues'] = $colors_hues;
    $constructor_page['colors_luminosities'] = $colors_luminosities;
    $constructor_page['colors'] = $colors;
    $constructor_page['color_icons'] = $color_icons;
  

    for($i = 0; $i<count($constructor_page['categ_parts']); $i++){
      $constructor_page['categ_parts'][$i]->image = $this->url . $this->path_root . $constructor_page['categ_parts'][$i]->image;
    }

    return response()->json(array('constructor_page'=> $constructor_page), 200);
  }


  public function get_petBackgrounds(Request $request){
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
    header('Access-Control-Allow-Headers: Content-Type');


    $petBgs = pet_backgrounds::select('id', 'active', 'image', 'locked')->get();
    for($i = 0; $i<count($petBgs); $i++){
      $petBgs[$i]->image = $this->url . $this->path_root . $petBgs[$i]->image;
    }

    $petBackgrounds['petBgs'] = $petBgs;

    return response()->json(array('petBackgrounds'=> $petBackgrounds), 200);
  
  //  return response()->json(
  //   array('petBackgrounds' => array(
  //     'petBgs' => pet_backgrounds::select('id', 'active', 'image', 'locked')
  //                               ->get()
  //   )),
  //  200);
  }


}


