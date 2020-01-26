<?php

namespace App\Matcher;

use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductsMatcher
 * @package App\Matcher
 */
class ProductsMatcher
{
    /**
     * @var array
     */
    protected static $plugins = [];

    /**
     * ProductsMatcher constructor.
     * @param PluginsFactory $plugins
     */
    public function __construct(PluginsFactory $plugins)
    {
        if (empty(static::$plugins)) {
            static::$plugins = $plugins->getPlugins();
        }
    }

    /**
     * @param Product $product
     * @return array
     */
    public function findPossibleMatches(Product $product) : array
    {
        $matches = [];

        if (!empty(static::$plugins)) {
            foreach (static::$plugins as $plugin) {
                /** @var MatcherPluginInterface $plugin */

                $matches = array_merge($matches, $plugin->match($product));
            }
        }

        $existing = DB::table('product_matches')->where('product_id', $product->id)->get()->map(function ($row) {
            return $row->possible_match_id;
        })->toArray();

        return array_filter(array_unique($matches), function ($match) use ($existing) {
            return !in_array($match, $existing);
        });
    }

    /**
     * @param Product $product
     * @param array $matches
     */
    public function logMatches(Product $product, array $matches)
    {
        foreach ($matches as $match) {
            DB::table('product_matches')->insert([
                'product_id' => $product->id,
                'possible_match_id' => $match,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }
    }
}
