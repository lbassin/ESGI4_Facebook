<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Api\Event as EventApi;
use App\Http\Helpers\FacebookHelper;
use App\Model\Event;
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
     * EventController constructor.
     * @param FacebookHelper $fbHelper
     */
    public function __construct(
        FacebookHelper $fbHelper
    )
    {
        $this->fbHelper = $fbHelper;
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
        /** @var array $events */
        $events = $request->post('eventsEdited') ?: [];

        foreach ($events as $id => $data) {
            /** @var Event $event */
            $event = Event::where(Event::ID, $id)->first();
            if (empty($event)) {
                $event = new Event([Event::ID => $id]);
            }

            $data[Event::VISIBLE] = !empty($data['visible']) && $data['visible'] == 'true';

            $event->fill($data);
            $event->save();
        }

        return response()->json([
            'message' => 'Evenements sauvegardés',
            'url' => route('dashboard.website', ['subdomain' => $subdomain])
        ]);
    }
}