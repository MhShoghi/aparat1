<?php


namespace App\Services;


use App\Category;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\ListCategoriesRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService extends BaseService
{

    public static function getAllCategories(ListCategoriesRequest $request)
    {

        $categories = Category::all();

        return $categories;

    }

    public static function getMyCategories(ListCategoriesRequest $request)
    {
        $categories = Category::where('user_id', auth()->id())->get();

        return $categories;
    }

    public static function uploadCategoryBanner(UploadCategoryBannerRequest $request)
    {
        try {
            $banner = $request->file('banner');

            $fileName = time() . Str::random('10') . '-cat-banner';

            Storage::disk('category')->put('tmp/' . $fileName, $banner->get());

            return response([
                'banner' => $fileName
            ], 200);
        } catch (\Exception $exception) {
            Log::error($exception);

            return response([
                'message' => 'Error has occurred!'
            ], 500);
        }
    }

    public static function createCategory(CreateCategoryRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->validated();
            $user = auth()->user();

            if($request->banner_id){
                $bannerPath = auth()->id() . '/' . $request->banner_id;
                Storage::disk('category')->move('tmp/'.$request->banner_id,$bannerPath);
            }

            $category = $user->categories()->create($data);

            DB::commit();
            return response($category,200);

        }catch (\Exception $exception){
            DB::rollBack();
            return response(['message' => 'Error has occurred!'],500);
        }

    }
}
