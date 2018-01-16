<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Api\Event as EventApi;
use App\Http\Helpers\FacebookHelper;
use App\Http\Helpers\WebsiteHelper;
use App\Model\Event;
use App\Model\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class EventController
 *
 * @author Laurent Bassin <laurent@bassin.info>
 */
class EventController extends BaseController
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
        /** @var EventApi $event */
        $event = $this->fbHelper->getEvent($id);

        return view('dashboard.website.event.details-modal', ['event' => $event]);
    }

    /**
     * @param Request $request
     * @param $subdomain
     * @return JsonResponse
     */
    public function saveAction(Request $request, $subdomain): JsonResponse
    {
        /** @var Website $website */
        $website = $this->websiteHelper->getCurrentWebsite();
        /** @var array $events */
        $events = $request->post('eventsEdited') ?: [];

        foreach ($events as $id => $data) {
            /** @var Event $event */
            $event = Event::where(Event::ID, $id)->first();
            if (empty($event)) {
                $event = new Event([Event::ID => $id]);
            }

            $data[Event::VISIBLE] = !empty($data['visible']) && $data['visible'] == 'true';
            $data[Event::WEBSITE_ID] = $website->getId();

            $event->fill($data);
            $event->save();
        }

        return response()->json([
            'message' => 'Evenements sauvegardés',
            'url' => route('dashboard.website', ['subdomain' => $subdomain])
        ]);
    }
}