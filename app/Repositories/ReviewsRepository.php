<?php

namespace App\Repositories;

use App\Exceptions\ReviewNotFoundException;
use App\ProductReview;
use Illuminate\Support\Arr;

/**
 * Class ReviewsRepository
 * @package App\Repositories
 */
class ReviewsRepository
{
    /**
     * @param array $data
     * @return ProductReview|bool
     * @throws ReviewNotFoundException
     */
    public function createOrUpdate(array $data)
    {
        if ($validated = $this->validate($data)) {
            if (!empty($data['id'])) {
                /** @var ProductReview $review */
                $review = ProductReview::find($data);

                if (is_null($review)) {
                    throw new ReviewNotFoundException("Review with ID {$data['id']} NOT FOUND.");
                }

                $review->update($validated);
            }
            else {
                $review = ProductReview::create($validated);
            }

            return $review;
        }

        return false;
    }

    /**
     * @param iterable $reviews
     * @throws ReviewNotFoundException
     */
    public function bulkCreateOrUpdate(iterable $reviews)
    {
        foreach ($reviews as $review) {
            $this->createOrUpdate($review);
        }
    }

    /**
     * @param array $data
     * @return array|bool
     */
    private function validate(array $data)
    {
        if (empty($data['author_id']) || empty($data['title']) || empty($data['published_at'])) {
            return false;
        }

        return $this->filter([
            'product_id'   => Arr::get($data, 'product_id', null),
            'author_id'    => (int)Arr::get($data, 'author_id'),
            'title'        => Arr::get($data, 'title'),
            'url'          => Arr::get($data, 'url'),
            'published_at' => Arr::get($data, 'published_at'),
            'pros'         => Arr::get($data, 'pros', null),
            'cons'         => Arr::get($data, 'cons', null),
            'likes_count'  => Arr::get($data, 'likes_count', null),
            'body'         => $this->clearBody(Arr::get($data, 'body', null)),
            'summary'      => Arr::get($data, 'summary', null),
            'bought_at'    => Arr::get($data, 'bought_at', null),
            'rating'       => Arr::get($data, 'rating', null),
            'i_recommend'  => Arr::get($data, 'i_recommend', null)
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function filter(array $data): array
    {
        return array_filter($data, function ($item) {
            return !is_null($item);
        });
    }

    /**
     * @param $body
     * @return string
     */
    private function clearBody($body): string
    {
        if (is_null($body)) {
            return $body;
        }

        // @TODO here we will clear body from things that we don't want to keep in our database, like images/tags etc
        return $body;
    }
}