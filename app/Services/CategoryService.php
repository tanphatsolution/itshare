<?php namespace App\Services;

use Session;
use Excel;
use DB;
use Config;
use Exception;
use Cache;

use App\Data\Blog\Category;
use App\Data\Blog\CategoryFilter;


class CategoryService
{
    const CATEGORIES_PER_PAGE = 12;
    const UPLOAD_IMAGE_PATH = 'uploads/images/';
    const POSTS_PER_CATEGORY = 5;
    const POSTS_PER_CATEGORY_DETAIL = 16;
    const HEADER_CATEGORIES = 'header_categories';
    const EXPORT_CATEGORIES_PATH = 'uploads/export';

    public static function getAllCategoriesName()
    {
        $categories = Category::select('name')->get()->toArray();
        $categories = array_pluck($categories, 'name');
        $result = [];
        foreach ($categories as $category) {
            $result[] = strip_tags(e($category));
        }
        return $result;
    }

    public static function prepareCategories($pageCount = 0, $limit = self::CATEGORIES_PER_PAGE)
    {
        $offset = $pageCount * $limit;
        $categories = Category::with([
            'posts' => function ($query) {
                $query->whereNotNull('published_at')->orderBy('created_at', 'desc')
                    ->take(self::POSTS_PER_CATEGORY);
            }
        ])->orderBy('posts_count', 'DESC')->take($limit)->skip($offset)->get();
        return $categories;
    }

    public static function create($input)
    {
        $config = Config::get('image');
        $destinationPath = $config['category']['upload_dir'];
        $imageName = sha1(time() . time());
        if (isset($input['image']) && !is_null($input['image'])) {
            $image = $destinationPath . '/' . $imageName;
            $input['image']->move($destinationPath, $imageName);
        }
        return Category::create([
            'name' => $input['name'],
            'short_name' => $input['short_name'],
            'img' => isset($image) ? $image : null,
        ]);
    }

    /**
     * @param array $input
     * @return Post
     */
    public static function update($id, $input)
    {
        $result = [
            'action' => 'CategoriesController@edit',
            'message' => null,
            'message_type' => 'success',
        ];
        $category = Category::find($id);
        $config = Config::get('image');
        $destinationPath = $config['category']['upload_dir'];
        $imageName = sha1(time() . time());
        if ($input['name'] == $category->name && isset($input['image']) && $input['image'] == null && $input['short_name'] == $category->shortName) {
            $result['message'] = trans('messages.category.nothing_edited');
            $result['message_type'] = 'warning';
            $error = true;
        }
        if (isset($input['image']) && !is_null($input['image'])) {
            $image = $destinationPath . '/' . $imageName;
            $input['image']->move($destinationPath, $imageName);
            $category->img = $image;
        }
        if (!isset($error)) {
            $category->name = $input['name'];
            $category->shortName = $input['short_name'];
            $category->save();
            $result['message'] = trans('messages.category.edit_success');
        }
        return $result;
    }

    public static function delete($id)
    {
        $category = Category::find($id);
        $message = trans('messages.error');
        if (!$category) {
            $message = trans('messages.category.not_exist', ['item' => $id]);
            return [$message, true];
        }
        if ($category->delete()) {
            $message = trans('messages.category.has_deleted', ['item' => $category->name]);
            return [$message, false];
        }
        return [$message, true];
    }

    public static function restore($id)
    {
        $category = Category::withTrashed()->find((int)$id);
        $message = trans('messages.error');
        if (!$category) {
            $message = trans('messages.category.not_exist', ['item' => $id]);
            return [$message, true];
        }
        if ($category->restore()) {
            $message = trans('messages.category.has_restored', ['item' => $category->name]);
            return [$message, false];
        }
        return [$message, true];
    }

    public static function getHeaderCategories()
    {
        if (Cache::has(self::HEADER_CATEGORIES)) {
            return Cache::get(self::HEADER_CATEGORIES);
        }
        $categories = self::prepareCategories(0, 20)->lists('name', 'short_name');
        Cache::put(self::HEADER_CATEGORIES, $categories, 1440);
        return $categories;
    }

    public static function createCategoryFilter($input)
    {
        $category = Category::find((int)$input['category_id']);
        $message = trans('messages.error');
        $result = CategoryFilter::create([
            'category_id' => $input['category_id'],
        ]);
        if ($result) {
            $message = trans('messages.category.has_filtered', ['item' => $category->name]);
            return [$message, false];
        }
        return [$message, true];
    }

    public static function deleteCategoryFilter($categoryId)
    {
        $categoryFilter = CategoryFilter::where('category_id', $categoryId)->first();
        $message = trans('messages.error');
        if (!$categoryFilter) {
            $message = trans('messages.category.filter_not_exist', ['item' => $categoryId]);
            return [$message, true];
        }
        if ($categoryFilter->delete()) {
            $message = trans('messages.category.has_unfiltered', ['item' => $categoryFilter->category->name]);
            return [$message, false];
        }
        return [$message, true];
    }

    public static function importCategoriesFrom($inputFile)
    {
        $result = [
            'error' => true,
            'total' => 0,
        ];
        $file = fopen($inputFile->getRealPath(), 'r');

        DB::beginTransaction();
        $keepStringTags = Config::get('character.special_allowed');
        $total = 0;

        while (!feof($file)) {
            try {
                $readFile = fgetcsv($file, 0, ',');
                $categoryName = strip_tags($readFile[0]);
                $shortName = strip_tags($readFile[1]);
                $shortName2 = convert_to_alias(convert_to_short_name($categoryName, $keepStringTags));
                $category = Category::where('short_name', $shortName2)
                    ->orWhere('name', $categoryName)
                    ->first();

                if (!$category && !empty($categoryName)) {
                    $category = Category::where('short_name', $shortName)->first();
                    if ($category) {
                        Category::create([
                            'name' => $categoryName,
                            'short_name' => $shortName2,
                        ]);
                    } else {
                        Category::create([
                            'name' => $categoryName,
                            'short_name' => $shortName,
                        ]);
                    }

                    $total++;
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return $result;
            }
        }

        $result = [
            'error' => false,
            'total' => $total,
        ];

        fclose($file);
        return $result;
    }

    public static function getExportCategories($filename, $type = 'csv')
    {
        Excel::create($filename, function ($excel) {
            $excel->sheet('Categories', function ($sheet) {
                $row = 1;
                $categories = Category::orderBy('name')->get();
                foreach ($categories as $category) {
                    $sheet->row($row, [
                        $category->name,
                        $category->short_name,
                    ]);
                    $row++;
                }
            });
        })->export($type);
    }
}
