<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeesResource;
use App\Http\Traits\ShouldLoadRelations;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttendeeController extends Controller
{
    use ShouldLoadRelations;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    private $relations = ['user'];
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $this->loadRelations($event->attendees()->latest());
        return AttendeesResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);

        return new AttendeesResource($this->loadRelations($attendee));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeesResource($this->loadRelations($attendee));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        if(Gate::denies('update-event', $event)){
            abort(403,'Denied access to this action');
        }

        $attendee->delete();

        return response()->json(['message' => 'deleted successfully']);
    }
}
