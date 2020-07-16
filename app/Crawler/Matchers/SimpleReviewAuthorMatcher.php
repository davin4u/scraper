<?php

namespace App\Crawler\Matchers;

use App\Crawler\Interfaces\Matchable;
use App\ReviewAuthor;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SimpleReviewAuthorMatcher
 * @package App\Crawler\Matchers
 */
class SimpleReviewAuthorMatcher implements Matchable
{
    /**
     * @var array
     */
    protected $matchableProps = ['platform', 'country_id', 'city_id'];

    /**
     * @param string $name
     * @param array $props
     * @param bool $returnModel
     * @return int|Model
     */
    public function match(string $name, array $props = [], bool $returnModel = false)
    {
        $query = ReviewAuthor::query();

        if (!empty($props)) {
            foreach ($props as $propName => $propValue) {
                if (in_array($propName, $this->matchableProps)) {
                    $query->where($propName, $propValue);
                }
            }
        }

        $query->where('name', $name);

        $author = $query->first();

        if (is_null($author) && !empty($props['platform'])) {
            $author = ReviewAuthor::create([
                'name'        => $name,
                'platform'    => $props['platform'],
                'profile_url' => isset($props['profile_url']) ? $props['profile_url'] : null
            ]);
        }

        return $returnModel ? $author : (int)$author->id;
    }
}