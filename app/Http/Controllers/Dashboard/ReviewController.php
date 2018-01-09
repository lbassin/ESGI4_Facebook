<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Api\Review as ReviewApi;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Review;
use App\Model\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class ReviewController extends BaseController
{
    /**
     * @var FacebookHelper
     */
    private $fbHelper;
    /**
     * @var WebsiteHelper
     */
    private $websiteHelper;

    /**
     * EventController constructor.
     * @param FacebookHelper $fbHelper
     * @param WebsiteHelper $websiteHelper
     */
    public function __construct(
        FacebookHelper $fbHelper,
        WebsiteHelper $websiteHelper
    )
    {
        $this->fbHelper = $fbHelper;
        $this->websiteHelper = $websiteHelper;
    }

    /**
     * @param Request $request
     * @return View
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function detailsAction(Request $request): View
    {
        /** @var string $id */
        $id = $request->post('id');
        /** @var array $splitedId */
        $splitedId = explode('.', $id);
        if (empty($splitedId[1])) {
            die('error'); // TODO
        }

        /** @var string $reviewerId */
        $reviewerId = $splitedId[1];
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var ReviewApi $review */
        $review = $this->fbHelper->getReview($website, $reviewerId);

        return view('dashboard.website.review.details-modal', ['review' => $review]);
    }

    public function saveAction(Request $request): JsonResponse
    {
        /** @var array $reviews */
        $reviews = $request->post('reviewsEdited');


        foreach ($reviews as $id => $data) {
            /** @var array $ids */
            $ids = explode('.', $id);

            if (empty($ids[0]) || empty($ids[1])) {
                continue;
            }

            /** @var string $sourceId */
            $sourceId = $ids[0];
            /** @var string $reviewerId */
            $reviewerId = $ids[1];

            /** @var Review $review */
            $review = Review::where(Review::SOURCE_ID, $sourceId)->where(Review::REVIEWER_ID, $reviewerId)->first();
            if (empty($review)) {
                $review = new Review([Review::SOURCE_ID => $sourceId, Review::REVIEWER_ID => $reviewerId]);
            }

            $data[Review::VISIBLE] = !empty($data['visible']) && $data['visible'] == 'true';

            $review->fill($data);
            $review->save();
        }

        return response()->json([]);
    }
}