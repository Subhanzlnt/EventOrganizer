<?php

namespace app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendeesApiController extends ApiBaseController
{

    /**
     * @param Request $request
     * @return mixed
     */

    public function index(Request $request)
    {
        return Attendee::scope($this->account_id)->paginate($request->get('per_page', 25));
    }


    /**
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function show(Request $request, $attendee_id)
    {
        if ($attendee_id) {
            return Attendee::scope($this->account_id)->find($attendee_id);
        }

        return response('Attendee Not Found', 404);
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

    public function showattendeesevent(Request $request)
    {
        $event_id = $request->get('event_id');
        $result = [
            'success' => true,
            'status' => 200,
            'message' => 'Berhasil mengambil data',
        ];

        $attendees = Attendee::select(
            'attendees.id',
            'attendees.event_id',
            'attendees.first_name',
            'attendees.last_name',
            'attendees.email',
            'attendees.private_reference_number',
            'attendees.has_arrived',
            'attendees.phone_number',
            'attendees.school',
            'orders.order_reference'
        )
            ->leftJoin('orders', 'orders.id', '=', 'attendees.order_id')

            ->where('attendees.event_id', $event_id)
            ->where('attendees.is_cancelled', 0)
            ->where('attendees.is_refunded', 0)
            ->get();
        /*$attendees = Attendee::with('order:order_reference')
            ->where('attendees.event_id', $event_id)
            ->where('attendees.is_cancelled',0)
            ->where('attendees.is_refunded',0)
            ->get();*/
        /*dd($attendees);*/
        $result['data']['attendees'] = $attendees;

        return $result;
    }

    public function checkinattendee(Request $request)
    {
        $result = [
            'status' => 200,
        ];
        $event_id = $request->get('event_id');
        $private_reference_number = $request->get('private_reference_number');
        $checking = $request->get('checking');

        $attendee = Attendee::where('event_id', $event_id)
            ->where('private_reference_number', $private_reference_number);
        $row_attende = $attendee->first();

        if ($attendee->exists()) {
            if ($row_attende['is_cancelled'] == 1 || $row_attende['is_refunded'] == 1) {
                $result['success'] = false;
                $result['message'] = "Tiket $row_attende[first_name] $row_attende[last_name] di cancel atau di refund";
            } else {
                if ((($checking == 'in') && ($row_attende['has_arrived'] == 1)) || (($checking == 'out') && ($row_attende['has_arrived'] == 0))) {
                    $result['success']  = false;
                    $result['message']  = 'Attendee Already Checked ' . (($checking == 'in') ? 'In (at ' . $row_attende->arrival_time->format('H:i A, F j') . ')' : 'Out') . '!';
                } else {
                    $row_attende->has_arrived = ($checking == 'in') ? 1 : 0;
                    $row_attende->arrival_time = Carbon::now();
                    $row_attende->save();

                    $result['success'] = true;
                    $result['message'] = "Berhasil check $checking $row_attende[first_name] $row_attende[last_name]";
                }
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Tiket tidak terdaftar';
        }
        return $result;
    }
}
