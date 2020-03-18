<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\ListCategoriesRequest;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     * @param ListCategoriesRequest $request
     * @return \App\Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(ListCategoriesRequest $request)
    {
        return CategoryService::getAllCategories($request);
    }

    /**
     * Get personal categories
     * @param ListCategoriesRequest $request
     * @return mixed
     */
    public function myCategory(ListCategoriesRequest $request)
    {
        return CategoryService::getMyCategories($request);
    }

    /**
     * Create category
     * @param CreateCategoryRequest $request
     */
    public function createCategory(CreateCategoryRequest $request){
        return CategoryService::createCategory($request);
    }

    /**
     * Upload category banner
     * @param UploadCategoryBannerRequest $request
     */
    public function uploadBanner(UploadCategoryBannerRequest $request){
        return CategoryService::uploadCategoryBanner($request);
    }
}
