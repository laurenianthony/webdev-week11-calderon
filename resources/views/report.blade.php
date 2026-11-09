<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Events Data Model Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; color: #333; }
        h1 { color: #1a202c; border-bottom: 2px solid #cbd5e1; padding-bottom: 10px; }
        section { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h2 { font-size: 1.25rem; color: #2563eb; margin-top: 0; }
        ul { margin: 10px 0 0 20px; }
        li { margin-bottom: 6px; }
        .tag { background: #e2e8f0; color: #334155; font-size: 0.85em; padding: 2px 8px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Campus Events Relational Report</h1>

    <!-- Query 1 -->
    <section>
        <h2>1. Every venue with the number of events it hosts</h2>
        <ul>
            @foreach($venuesWithCount as $venue)
                <li><strong>{{ $venue->name }}</strong> ({{ $venue->location }}) — <strong>{{ $venue->events_count }} events</strong></li>
            @endforeach
        </ul>
    </section>

    <!-- Query 2 -->
    <section>
        <h2>2. All events at chosen venue: "{{ $chosenVenue->name }}"</h2>
        <ul>
            @forelse($chosenVenue->events as $event)
                <li>{{ $event->title }} — <em>Scheduled: {{ $event->scheduled_at }}</em></li>
            @empty
                <li>No events found for this venue.</li>
            @endforelse
        </ul>
    </section>

    <!-- Query 3 -->
    <section>
        <h2>3. All participants of chosen event: "{{ $chosenEvent->title }}"</h2>
        <ul>
            @forelse($chosenEvent->participants as $participant)
                <li>{{ $participant->name }} ({{ $participant->email }}) — Status: <span class="tag">{{ $participant->pivot->status }}</span></li>
            @empty
                <li>No participants registered.</li>
            @endforelse
        </ul>
    </section>

    <!-- Query 4 -->
    <section>
        <h2>4. All events joined by participant: "{{ $chosenParticipant->name }}"</h2>
        <ul>
            @forelse($chosenParticipant->events as $event)
                <li>{{ $event->title }} — Status: <span class="tag">{{ $event->pivot->status }}</span></li>
            @empty
                <li>No events joined.</li>
            @endforelse
        </ul>
    </section>

    <!-- Query 5 -->
    <section>
        <h2>5. All events with at least one waitlisted participant</h2>
        <ul>
            @foreach($eventsWithWaitlisted as $event)
                <li><strong>{{ $event->title }}</strong></li>
            @endforeach
        </ul>
    </section>

    <!-- Query 6 -->
    <section>
        <h2>6. All notes of chosen Event & Venue</h2>
        <strong>Notes for Event ("{{ $chosenEvent->title }}"):</strong>
        <ul>
            @forelse($eventNotes as $note)
                <li>{{ $note->body }}</li>
            @empty
                <li>No notes attached to this event.</li>
            @endforelse
        </ul>
        <br>
        <strong>Notes for Venue ("{{ $chosenVenue->name }}"):</strong>
        <ul>
            @forelse($venueNotes as $note)
                <li>{{ $note->body }}</li>
            @empty
                <li>No notes attached to this venue.</li>
            @endforelse
        </ul>
    </section>

    <!-- Query 7 -->
    <section>
        <h2>7. Polymorphic Note Details</h2>
        <p><strong>Note ID:</strong> #{{ $chosenNote->id }}</p>
        <p><strong>Note Content:</strong> "{{ $chosenNote->body }}"</p>
        <p><strong>Belongs To Type:</strong> <span class="tag">{{ $chosenNote->notable_type }}</span></p>
        <p><strong>Belongs To Name:</strong> {{ $chosenNote->notable->name ?? $chosenNote->notable->title }}</p>
    </section>

</body>
</html>