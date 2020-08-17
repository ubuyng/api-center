<?php

namespace App\Http\Controllers;

use App\Category;
use App\SubCategory;
use App\Project;
use App\Response;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\ResponseItem;


class ExploreController extends Controller
{
 


    public function singleCategory($slug)
    {

        $id = DB::table('categories')->where('slug', $slug)->value('id');

        // $id = base64_decode(str_pad(strtr($slug, '-_', '+/'), strlen($slug) % 4, '=', STR_PAD_RIGHT))/786;

        $category = Category::find($id);
        $categories = Category::unDeleted()->get()->take(5);
        $subCategoriesHasImages = SubCategory::unDeleted()->hasImage()->data(['category_id' => $id])->orderBy('count', 'DESC')->get()->take(12);
        $subCategories = SubCategory::unDeleted()->data(['category_id' => $id])->orderBy('name', 'asc')->get();
        $title = $category->name.' | UBUY.NG';

        return view('category-group', compact('category', 'categories', 'subCategories', 'subCategoriesHasImages', 'title'));
    }
    public function ExploreUbuy()
    {

        $title = 'Explore | UBUY.NG';
        $profiles = Profile::lat()->lng()->get();
        $categories = Category::unDeleted()->get()->take(5);
        $subCategories = SubCategory::unDeleted()->orderBy('count', 'DESC')->get()->take(10);
    	$subCategoriesHasImages = SubCategory::unDeleted()->orderBy('count', 'DESC')->get()->take(10);

        return view('explore', compact('categories', 'subCategories', 'profiles', 'subCategoriesHasImages', 'title'));
    }
    public function singleSubCategory($slug)
    {

        $id = DB::table('sub_categories')->where('slug', $slug)->value('id');

        
        $subCategory = SubCategory::find($id);
        $questions = $subCategory->questions;
        $category = $subCategory->category;
        $providers = ResponseItem::data(['sub_category_id' => $id, 'user_type' => 1])->with('users')->get();
        $count = $subCategory->count++;
        $subCategory->update(['count' => $count]);
        $title = $subCategory->name.' | UBUY.NG';

        return view('sub-category', compact('category', 'subCategory', 'questions', 'providers', 'slug','title'));

    }


    public function SearchSubCategory($slug)
    {

        $id = DB::table('sub_categories')->where('slug', $slug)->value('id');


        $subCategory = SubCategory::find($id);
        $questions = $subCategory->questions;
        $category = $subCategory->category;
        $providers = ResponseItem::data(['sub_category_id' => $id, 'user_type' => 1])->with('users')->get();
        $count = $subCategory->count++;
        $subCategory->update(['count' => $count]);
        $title = $subCategory->name.' | UBUY.NG';

        return view('sub-category', compact('category', 'subCategory', 'questions', 'providers', 'slug','title'));

    }
    public function singleSubCategoryAuth($slug)
    {

        $id = DB::table('sub_categories')->where('slug', $slug)->value('id');


        $subCategory = SubCategory::find($id);
        $questions = $subCategory->questions;
        $category = $subCategory->category;
        $providers = ResponseItem::data(['sub_category_id' => $id, 'user_type' => 1])->with('users')->get();
        $count = $subCategory->count++;
        $subCategory->update(['count' => $count]);
        $title = $subCategory->name.' | UBUY.NG';

        return view('sub-category', compact('category', 'subCategory', 'questions', 'providers', 'slug','title'));

    }

    
    public function storeResponse(Request $request)
    {
        $user = auth()->user();
        
    

        // echo $project_id;  
 
        $responseData = Project::data(['sub_category_id' => $request->sub_category_id, 'user_id' => $user->id ])->unDeleted()->notClosed()->first();

       

        if($request->update == 1){

            $response = Response::data(['sub_category_id' => $request->sub_category_id, 'user_id' => $user->id ])->unDeleted()->delete();

            Project::data(['sub_category_id' => $request->sub_category_id, 'user_id' => $user->id ])->unDeleted()->delete();
            $responseData = '';
        }

        if($responseData){
            // echo $e->getMessage();
            return redirect()->back()->withErrors('A similar project already has been saved by you');
        }

        $dataLocation['user_id']         = $user->id;
        // $dataLocation['user_type']       = $request->user_type;
        $dataLocation['sub_category_id'] = $request->sub_category_id;
        $dataLocation['sub_category_name'] = $request->sub_category_name;
        $dataLocation['sub_category_slug'] = $request->sub_category_slug;
        $dataLocation['project_message']         = $request->message;
        $dataLocation['cus_name']         = $request->cus_name;
        $dataLocation['lat']             =  $request->lat;
        $dataLocation['lng']             =  $request->lng;
        $dataLocation['address']         =  $request->address;
        $dataLocation['city']            =  $request->city;
        $dataLocation['state']           =  $request->state;
        $dataLocation['phone_number']    =  $user->number;
        $dataLocation['user_role']    =  $user->user_role;

        $project_id  =  Project::create($dataLocation)->id;
        if(isset($request->checkbox)){
            foreach($request->checkbox as $key => $option){
                $data['question_id']     = $option;
                $data['sub_category_id'] = $request->sub_category_id;
                $data['choice_id']       = $key;
                $data['user_id']         = $user->id;
                $data['project_id']         = $project_id;
                
                Response::create($data);
            }
        }

        if(isset($request->radio)){
            foreach($request->radio as $key => $option){
                $radioData['question_id']     = $key;
                $radioData['sub_category_id'] = $request->sub_category_id;
                $radioData['choice_id']       = $option;
                $radioData['user_id']         = $user->id;
                $radioData['project_id']         = $project_id;

                Response::create($radioData);
            }
        }

        $id = base64_encode($project_id*786);

        return redirect()->to("/dashboard/projects/bids/$id");
    }

    
    
    public function search_subcat(Request $request){
        $sub_id = $request->category_id;
        $subCategory = SubCategory::find($sub_id);
        
      $sub_slug = $subCategory->slug;
        return redirect()->to("/sub-category/$sub_slug");

    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * Clear all cache
     */
    public function clearCache(){
        Artisan::call('debugbar:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        if (function_exists('exec')){
            exec('rm ' . storage_path('logs/*'));
        }
        return redirect(route('home'));
    }

}
