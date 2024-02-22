<?php

namespace App\Transformers\API;

use App\Models\OrderReview;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class OrderReviewTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'user'
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(OrderReview $review)
    {
        return [
            'rating'        => $review->rating,
            'review'        => $review->review,
            'images'        => $this->images($review),
            'date'          => $review->created_at->format('d-m-Y H:i'),
        ];
    }

    private function images($review)
    {
        return is_null($review->imageable) ? [] : $review->imageable->map(function ($item) {
            return asset('image/review/'.$item->name);
        });
    }

    public function includeUser($review)
    {
        $user = $review->user;
        if ($user instanceof User) {
            return $this->item($user, new UserTransformer());
        } else {
            return $this->null();
        }        
    }

}
