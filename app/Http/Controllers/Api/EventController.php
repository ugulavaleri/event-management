<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\ShouldLoadRelations;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use ShouldLoadRelations;

    private $relations = ['user','attendees','attendees.user'];



    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index, show']);
    }

    public function index()
    {
        // we have fully, optional control what can client take from this endpoint
        $query = $this->loadRelations(Event::query());
        return EventResource::collection($query->latest()->get());
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
           'name' => 'required|string',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|after:start_time|date'
        ]);

        $event = Event::create([...$data,'user_id' => $request->user()->id]);

        return new EventResource($this->loadRelations($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($this->loadRelations($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {

        if(Gate::denies('update-event', $event)){
            abort(403,'Denied access to this action');
        }

        $data = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|after:start_time|date'
        ]);
        $event->update($data);

        return new EventResource($this->loadRelations($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'message' => 'Event Deleted Successfully!'
        ]);
    }
}
