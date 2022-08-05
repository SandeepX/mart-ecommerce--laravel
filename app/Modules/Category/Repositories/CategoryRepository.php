<?php

namespace App\Modules\Category\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Category\Models\CategoryMaster;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryRepository
{

  use ImageService;


  public function getCategoryMaster($select = "*"){
      return CategoryMaster::select($select)->latest()->get();
  }

  public function getAllCategories()
  {
    // return CategoryMaster::where('upper_category_code', null)->with('childCategories.childCategories')->get();
    $query = "WITH RECURSIVE category_path (id, category_code, category_name, remarks, slug, category_image, category_icon, path) AS
      (
        SELECT id, category_code, category_name, remarks, slug, category_image, category_icon, CAST(category_name AS CHAR(255)) as path
          FROM category_master
          WHERE upper_category_code IS NULL
        UNION ALL
        SELECT c.id, c.category_code, c.category_name, c.remarks, c.slug,c.category_image,c.category_icon, CONCAT(cp.path, ' > ', c.category_name)
          FROM category_path AS cp JOIN category_master AS c
            ON cp.category_code = c.upper_category_code
      )
      SELECT * FROM category_path
      ORDER BY path";

    $categories = DB::select($query);
    return $categories;
  }


    public function searchCategoryPath($searchTerm)
    {
        // return CategoryMaster::where('upper_category_code', null)->with('childCategories.childCategories')->get();
        $query = "WITH RECURSIVE category_path ( category_code, category_name, path) AS
      (
        SELECT  category_code, category_name, CAST(category_name AS CHAR(255)) as path
          FROM category_master
          WHERE upper_category_code IS NULL
        UNION ALL
        SELECT c.category_code, c.category_name, CONCAT(cp.path, ' > ', c.category_name)
          FROM category_path AS cp JOIN category_master AS c
            ON cp.category_code = c.upper_category_code
      )
      SELECT * FROM category_path
      where path like '%$searchTerm%'
      ORDER BY path";

        $categories = DB::select($query);
        return $categories;
    }


  public function getCategoryFamily($categoryCode)
  {
    $query = "WITH RECURSIVE category_path (id, category_code, category_name, upper_category_code) AS
      (
        SELECT id, category_code, category_name, upper_category_code
          FROM category_master
          WHERE category_code = '$categoryCode' -- child node
        UNION ALL
        SELECT c.id, c.category_code, c.category_name, c.upper_category_code
          FROM category_path AS cp JOIN category_master AS c
            ON cp.upper_category_code = c.category_code
      )
      SELECT * FROM category_path
      ORDER BY category_path.id ASC";


    $categories = DB::select($query);
    return $categories;
  }


  public function getRootCategories($with = [])
  {
    return CategoryMaster::with($with)->where('upper_category_code', null)->get();
  }

  public function getCategoryTree()
  {
    return $this->getRootCategories('lowerCategories');
  }



  public function getRootCategoriesHavingBrands($with = [])
  {
    return CategoryMaster::whereHas('brands')->with($with)->where('upper_category_code', null)->get();
  }

  public function getRootCategoriesNotHavingBrands()
  {
    return CategoryMaster::whereDoesntHave('brands')->where('upper_category_code', null)->get();
  }

  public function getLowerCategories($category)
  {
    return $category->lowerCategories;
  }

    public function getDaddyWithHisSiblingCategories($childCategoryCode){
      $bindings= [
          'child_category_code' => $childCategoryCode
      ];

        /*---if category incoming is root or first generation ------*/
       $rootCategoryExecution = DB::select("
             select upper_category_code from category_master where category_code=:child_category_code
        ",$bindings);

        $rootLevelCatValue = collect($rootCategoryExecution)->pluck('upper_category_code')[0];
        if(is_null($rootLevelCatValue)){
            return collect();
        }
        /*---End :if category incoming is root or first generation ---- */


        /*---if category incoming is second generation------*/
        $checkSecondLevelCategoryQuery = "
          select upper_category_code from category_master where category_code= (
             select upper_category_code from category_master where category_code=:child_category_code
         )
        ";
        $secondLevelCheckExecution = DB::select($checkSecondLevelCategoryQuery,$bindings);
        $secondLevelCatValue = collect($secondLevelCheckExecution)->pluck('upper_category_code')[0];
        //is NULL- yes => second gen categories
        if(is_null($secondLevelCatValue)){
           $queryForSecondLevelDaddies = "
             select category_code from category_master where upper_category_code is NULL
           ";
           $secondGenDaddies = DB::select($queryForSecondLevelDaddies);
            $secondGenDaddies = collect($secondGenDaddies)
                                ->pluck('category_code')
                                ->toArray();
            return CategoryMaster::whereIn('category_code',$secondGenDaddies)->get();
        }

        /*---End :if category incoming is second generation ---- */

      $query = "
        select category_code from category_master where upper_category_code = (
           select upper_category_code from category_master where category_code= (
             select upper_category_code from category_master where category_code=:child_category_code
         )
      )
      ";
      $daddyWithHisSiblingsCodes = DB::select($query,$bindings);
      $daddyWithHisSiblingsCodes = collect($daddyWithHisSiblingsCodes)
                                         ->pluck('category_code')
                                         ->toArray();
      return CategoryMaster::whereIn('category_code',$daddyWithHisSiblingsCodes)->get();
    }


  public function getCategoryByCode($categoryCode, $with = [])
  {
    return CategoryMaster::with($with)->findOrfail($categoryCode);
  }

    public function getCategoryByCatSlug($categorySlug, $with = [])
    {
        return CategoryMaster::with($with)->findOrfail($categorySlug);
    }

  public function getCategoriesByCodes(array $categoryCodes, $with = [])
  {
    return CategoryMaster::with($with)->whereIn('category_code', $categoryCodes)->get();
  }

  public function getCategoriesBySlugs(array $categorySlugs, $with = [])
  {
    return CategoryMaster::with($with)->whereIn('slug', $categorySlugs)->get();
  }

  public function getCategoryBySlug($categorySlug)
  {
    return CategoryMaster::where('slug', $categorySlug)->firstOrFail();
  }

  public function getBrandsByCategory($category)
  {
    return $category->brands;
  }

  public function create($validated)
  {
    try {
      $category = new CategoryMaster;
      $authUserCode = getAuthUserCode();
      $validated['category_code'] = $category->generateCategoryCode();
      $validated['created_by'] = $authUserCode;
      $validated['updated_by'] = $authUserCode;
      $validated['slug'] = Str::slug($validated['category_name']);
      if (isset($validated['category_banner'])) {
          $validated['category_banner'] = $this->storeImageInServer($validated['category_banner'], CategoryMaster::BANNER_UPLOAD_PATH);
      }
      if (isset($validated['category_image'])) {
        $this->deleteImageFromServer(CategoryMaster::CATEGORY_IMAGE_UPLOAD_PATH, $category->category_image);
        $validated['category_image'] = $this->storeImageInServer($validated['category_image'], CategoryMaster::CATEGORY_IMAGE_UPLOAD_PATH);
      }
      if (isset($validated['category_icon'])) {
        $this->deleteImageFromServer(CategoryMaster::CATEGORY_ICON_UPLOAD_PATH, $category->category_icon);
        $validated['category_icon'] = $this->storeImageInServer($validated['category_icon'], CategoryMaster::CATEGORY_ICON_UPLOAD_PATH);
      }

      $category = CategoryMaster::create($validated);

      //sync category to category_type
      $category->categoryTypes()->sync($validated['category_type_code']);
      return $category->fresh();
    } catch (Exception $ex) {
      $this->deleteImageFromServer(CategoryMaster::BANNER_UPLOAD_PATH, $validated['category_banner']);
      $this->deleteImageFromServer(CategoryMaster::CATEGORY_IMAGE_UPLOAD_PATH, $validated['category_image']);
      $this->deleteImageFromServer(CategoryMaster::CATEGORY_ICON_UPLOAD_PATH, $validated['category_icon']);
      throw $ex;
     }
  }

  public function update($validated, $categoryCode)
  {

    try {

      $authUserCode = getAuthUserCode();
      $category = $this->getCategoryByCode($categoryCode);


      if (isset($validated['category_banner'])) {
        $this->deleteImageFromServer(CategoryMaster::BANNER_UPLOAD_PATH, $category->category_banner);
        $validated['category_banner'] = $this->storeImageInServer($validated['category_banner'], CategoryMaster::BANNER_UPLOAD_PATH);
      }
        if (isset($validated['category_image'])) {
            $this->deleteImageFromServer(CategoryMaster::CATEGORY_IMAGE_UPLOAD_PATH, $category->category_image);
            $validated['category_image'] = $this->storeImageInServer($validated['category_image'], CategoryMaster::CATEGORY_IMAGE_UPLOAD_PATH);
        }
        if (isset($validated['category_icon'])) {
            $this->deleteImageFromServer(CategoryMaster::CATEGORY_ICON_UPLOAD_PATH, $category->category_icon);
            $validated['category_icon'] = $this->storeImageInServer($validated['category_icon'], CategoryMaster::CATEGORY_ICON_UPLOAD_PATH);
        }

      $validated['updated_by'] = $authUserCode;
      $validated['slug'] = Str::slug($validated['category_name']);
      $category->update($validated);

      //sync category to category_type
      $category->categoryTypes()->sync($validated['category_type_code']);
      return $category;
    } catch (Exception $ex) {
      $this->deleteImageFromServer(CategoryMaster::BANNER_UPLOAD_PATH, $validated['category_banner']);
      $this->deleteImageFromServer(CategoryMaster::CATEGORY_IMAGE_UPLOAD_PATH, $validated['category_image']);
      $this->deleteImageFromServer(CategoryMaster::CATEGORY_ICON_UPLOAD_PATH, $validated['category_icon']);
      throw $ex;
    }
  }

  public function delete($categoryCode)
  {
    $category = $this->getCategoryByCode($categoryCode);
    $check = $category->canDelete('lowerCategories', 'brands', 'categoryTypes', 'products');  //Checking if Relation exists between other models

    if (!$check['can']) {
      throw new Exception('Sorry Category Cannot Be Deleted!! This Category Contains ' . $check['relation'] . ' !! Please Delete them First to delete this Category', 402);
    }
    // $category->delete();
    // $category->deleted_by = 1;
    // $category->save();
    return $category;
  }

  public function syncCategoryBrands($category, $validated)
  {
    // foreach($validated)
    $category->brands()->sync($validated);
    return $category;
  }
}
