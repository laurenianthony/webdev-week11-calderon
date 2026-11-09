<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Note;
use App\Models\Participant;
use App\Models\Venue;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Every venue with the number of events it hosts
        $venuesWithCount = Venue::withCount('events')->get();

        // 2. All events at one chosen venue
        $chosenVenue = Venue::with('events')->first();

        // 3. All participants of one chosen event, with each person's registration status
        $chosenEvent = Event::with('participants')->first();

        // 4. All events one chosen participant has joined
        $chosenParticipant = Participant::with('events')->first();

        // 5. All events that have at least one waitlisted participant
        $eventsWithWaitlisted = Event::whereHas('participants', function ($query) {
            $query->where('status', 'waitlisted');
        })->with('participants')->get();

        // 6. All notes of one chosen event, and all notes of one chosen venue
        $eventNotes = $chosenEvent ? $chosenEvent->notes : collect();
        $venueNotes = $chosenVenue ? $chosenVenue->notes : collect();

        // 7. For one chosen note, the name of the thing it belongs to and what kind of thing it is
        $chosenNote = Note::with('notable')->first();

        return view('report', compact(
            'venuesWithCount',
            'chosenVenue',
            'chosenEvent',
            'chosenParticipant',
            'eventsWithWaitlisted',
            'eventNotes',
            'venueNotes',
            'chosenNote'
        ));
    }
}