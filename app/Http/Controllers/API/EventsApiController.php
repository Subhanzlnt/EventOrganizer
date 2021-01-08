<?php

namespace app\Http\Controllers\API;

use App\Models\Attendee;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsApiController extends ApiBaseController
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return Event::scope($this->account_id)->paginate(20);
    }

    /**
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function show(Request $request, $attendee_id)
    {
        if ($attendee_id) {
            return Event::scope($this->account_id)->find($attendee_id);
        }

        return response('Event Not Found', 404);
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request)
    {
    }

    public function destroy(Request $request)
    {
    }

    public function showtoday(Request $request)
    {
        $result = [
            'success' => true,
            'status' => 200,
            'message' => 'Berhasil mengambil data',
        ];


        $now = Carbon::now();
        /*$events = Event::where('start_date','=',$now->format('Y-m-d'))
            ->get(['id', 'title', 'start_date', 'end_date', 'venue_name']);
        dd($events);*/

        $events = Event::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orWhereDate('start_date', $now->format('Y-m-d'))
            ->get(['id', 'title', 'start_date', 'end_date', 'venue_name']);
        //kalo jamnya belum masuk gak bisa ni. untuk where terakhir cek untuk date aja
        /*$events = Event::whereDate('start_date', '=', $now)
            ->get(['id', 'title', 'start_date', 'end_date', 'venue_name']);*/
        foreach ($events as $key => $event) {
            //if else untuk tombol mulai atau belum
            $events[$key]['is_started_scan'] = ($now >= Carbon::parse($events[$key]['start_date'])->subHour()
                && $now <= $events[$key]['end_date'])
                ? true
                : false;
            //dump($events[$key]['start_date']);
            $full = Carbon::parse($events[$key]['start_date'])->format('d M Y H:i') . ' - ' . Carbon::parse($events[$key]['end_date'])->format('d M Y H:i');
            $time = Carbon::parse($events[$key]['start_date'])->format('H:i') . ' - ' . Carbon::parse($events[$key]['end_date'])->format('H:i');
            $events[$key]['time'] = (Carbon::parse($events[$key]['start_date'])->toDateString() == Carbon::parse($events[$key]['end_date'])->toDateString()) ? $time : $full;
            $events[$key]['present'] = Attendee::where('event_id', $events[$key]['id'])
                ->where('has_arrived', 1)
                ->where('is_cancelled', 0)
                ->where('is_refunded', 0)
                ->get()->count();
            $events[$key]['absent'] = Attendee::where('event_id', $events[$key]['id'])
                ->where('has_arrived', 0)
                ->where('is_cancelled', 0)
                ->where('is_refunded', 0)
                ->get()->count();
        }
        $result['data']['events'] = $events;

        return $result;
    }

    public function showall(Request $request)
    {
        $result = [
            'success' => true,
            'status' => 200,
            'message' => 'Berhasil mengambil data',
        ];

        $now = Carbon::now();
        /*$events = Event::all()->get(['id', 'title', 'start_date', 'end_date', 'venue_name']);*/
        $events = Event::select('id', 'title', 'start_date', 'end_date', 'venue_name')->get();

        foreach ($events as $key => $event) {
            //if else untuk tombol mulai atau belum
            $events[$key]['finished'] = ($now > $events[$key]['end_date'])
                ? true
                : false;
            //dump($events[$key]['start_date']);
            $full = Carbon::parse($events[$key]['start_date'])->format('d M Y H:i') . ' - ' . Carbon::parse($events[$key]['end_date'])->format('d M Y H:i');
            $time = Carbon::parse($events[$key]['start_date'])->format('d M Y') . ', ' . Carbon::parse($events[$key]['start_date'])->format('H:i') . ' - ' . Carbon::parse($events[$key]['end_date'])->format('H:i');
            $events[$key]['time'] = (Carbon::parse($events[$key]['start_date'])->toDateString() == Carbon::parse($events[$key]['end_date'])->toDateString()) ? $time : $full;
            $events[$key]['present'] = Attendee::where('event_id', $events[$key]['id'])
                ->where('has_arrived', 1)
                ->where('is_cancelled', 0)
                ->where('is_refunded', 0)
                ->get()->count();
            $events[$key]['absent'] = Attendee::where('event_id', $events[$key]['id'])
                ->where('has_arrived', 0)
                ->where('is_cancelled', 0)
                ->where('is_refunded', 0)
                ->get()->count();
        }
        $result['data']['events'] = $events;

        return $result;
    }
}
