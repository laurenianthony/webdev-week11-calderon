<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        // Fixed version: Eager load venue and count participants in bulk queries
        $events = Event::with('venue')
            ->withCount('participants')
            ->take(50)
            ->get();

        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'title' => $event->title,
                'venue_name' => $event->venue->name, // Pre-loaded, no extra query!
                'participants_count' => $event->participants_count, // Pre-counted!
            ];
        }

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        return response()->json([
            'fixed_query_count' => $queryCount,
            'events_sample' => array_slice($data, 0, 5),
        ]);
    }
    public function jsonbFilter()
    {
        // 1. Arrow form filter
        $query1 = Event::where('details->dress_code', 'Formal');
        $sql1 = $query1->toSql();
        $results1 = $query1->take(5)->get();

        // 2. whereJsonContains filter using our rare value "Astronaut"
        $query2 = Event::whereJsonContains('details->special_guest', 'Astronaut');
        $sql2 = $query2->toSql();
        $results2 = $query2->get();

        return response()->json([
            'arrow_form' => [
                'sql' => $sql1,
                'count' => $results1->count(),
            ],
            'json_contains' => [
                'sql' => $sql2,
                'count' => $results2->count(),
                'data' => $results2,
            ],
        ]);
    }
    
    public function fullTextSearch()
    {
        // 1. Basic LIKE search for "quantum"
        $query1 = Event::where('description', 'like', '%quantum%');
        $sql1 = $query1->toSql();
        $results1 = $query1->get();

        // 2. PostgreSQL Full-Text Search for "quantum"
        $query2 = Event::whereFullText('description', 'quantum');
        $sql2 = $query2->toSql();
        $results2 = $query2->get();

        return response()->json([
            'like_search' => [
                'sql' => $sql1,
                'count' => $results1->count(),
            ],
            'full_text_search' => [
                'sql' => $sql2,
                'count' => $results2->count(),
                'data' => $results2,
            ],
        ]);
    }
}