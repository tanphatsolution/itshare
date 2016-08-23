<?php
namespace App\Services;

use Carbon\Carbon;
use Config;

use App\Data\Blog\Contest;
use App\Data\Blog\Category;
use App\Data\Blog\Domain;
use App\Data\Blog\Post;


class ContestService
{
    public static function create($input)
    {
        $contest = Contest::create([
            'user_id' => $input['user_id'],
            'title' => $input['name'],
            'monthly_theme_subject_id' => $input['monthly_theme_subject_id'],
            'term_start' => Carbon::parse($input['start'])->format('Y-m-d H:i:s'),
            'term_end' => Carbon::parse($input['end'])->format('Y-m-d H:i:s'),
            'term_score_end' => Carbon::parse($input['score_end'])->format('Y-m-d H:i:s'),
        ]);
        return $contest;
    }
    
    public static function saveCategories($contest, $categories)
    {
        $categoriesId = self::processCategoriesId($categories);
        if (!empty($categoriesId)) {
            $contest->categories()->attach($categoriesId);
        }
    }

    public static function saveDomains($contest, $domains)
    {
        $domainsId = self::processDomainsId($domains);
        if (!empty($domainsId)) {
            $contest->domains()->attach($domainsId);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @property int $newCategory->id
     * @return Response
     *
     */
    private static function processCategoriesId($categories)
    {
        if (is_string($categories)) {
            $categories = explode(',', $categories);
        }

        $keep = Config::get('character.special_allowed');
        $categoriesId = [];
        foreach ($categories as $category) {
            $category = strip_tags($category);
            $shortName = convert_to_alias(convert_to_short_name($category, $keep));
            $cat = Category::where('short_name', $shortName)
                ->orWhere('name', $category)->first();

            if ($cat) {
                $categoriesId[] = $cat->id;
            } else {
                if (!empty($category)) {
                    $newCategory = Category::create([
                        'name' => $category,
                        'short_name' => $shortName,
                    ]);
                    $categoriesId[] = $newCategory->id;
                }
            }
        }
        $categoriesId = array_unique($categoriesId);
        return $categoriesId;
    }

    private static function processDomainsId($domains)
    {
        $domainsId = [];

        if (!empty($domains)) {
            if (is_string($domains)) {
                $domains = explode(',', $domains);
            }
            foreach ($domains as $domain) {
                $domain = strip_tags($domain);
                if (strlen($domain) > 0) {
                    $domain = ($domain[0] == '@') ? $domain : '@' . $domain;
                }
                $dom = Domain::where('name', $domain)->first();

                if ($dom) {
                    $domainsId[] = $dom->id;
                } else {
                    if (!empty($domain)) {
                        $newDomain = DomainService::create($domain);
                        $domainsId[] = $newDomain->id;
                    }
                }
            }
            $domainsId = array_unique($domainsId);
        }

        return $domainsId;
    }

    public static function getContestRankings($contest, $contestCategories)
    {
        $rankings = Post::with(['user', 'categories' => function ($query) use ($contestCategories) {
            $query->whereIn('category_id', $contestCategories);
        }]);
        if ($contestCategories) {
            $rankings = $rankings->has('categories');
        }

        $rankings = $rankings->validForContest($contest)
            ->groupBy('user_id')
            ->take(10)
            ->get();

        return self::filterRankPosts($rankings, $contestCategories);
    }

    public static function filterRankPosts($collection, $categories)
    {
        foreach ($collection as $key => $item) {
            // Remove rows with zero total score
            if ($item->total_stocks + $item->total_comments <= 0) {
                $collection->forget($key);
            }
            // Remove rows not having the categories required
            if (count($categories) > 0 && !$item->categories) {
                $collection->forget($key);
            }
        }
        return $collection;
    }
}
