<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Note;
use App\Models\Participant;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Venues (3)
        $v1 = Venue::firstOrCreate(['name' => 'Main Auditorium'], ['location' => 'PUP Calauan Building A']);
        $v2 = Venue::firstOrCreate(['name' => 'Innovation Lab'], ['location' => 'PUP Calauan Building B']);
        $v3 = Venue::firstOrCreate(['name' => 'Campus Plaza'], ['location' => 'Main Quadrangle']);
        $venues = [$v1, $v2, $v3];

        // 2. Participants (Your name + Classmates)
        $participantsData = [
            ['name' => 'Lauren Anthony Calderon', 'email' => 'lauren.calderon@pup.edu.ph'],
            ['name' => 'Norwin Austria', 'email' => 'norwin.austria@pup.edu.ph'],
            ['name' => 'Aldred Recana', 'email' => 'aldred.recana@pup.edu.ph'],
            ['name' => 'Mark Eduard Boligor', 'email' => 'mark.boligor@pup.edu.ph'],
            ['name' => 'Kurt John Caraig', 'email' => 'kurt.caraig@pup.edu.ph'],
            ['name' => 'Timothy Alvarez', 'email' => 'timothy.alvarez@pup.edu.ph'],
            ['name' => 'Mhyco Gabrielle Faulve', 'email' => 'mhyco.faulve@pup.edu.ph'],
            ['name' => 'Mark Glenn Cosico', 'email' => 'mark.cosico@pup.edu.ph'],
            ['name' => 'Aron Nidea', 'email' => 'aron.nidea@pup.edu.ph'],
            ['name' => 'Kyle Lowell Pronto', 'email' => 'kyle.pronto@pup.edu.ph'],
        ];

        foreach ($participantsData as $pData) {
            Participant::firstOrCreate(['email' => $pData['email']], $pData);
        }

        // Fill up to at least 200 participants manually
        $currentCount = Participant::count();
        if ($currentCount < 200) {
            $extra = [];
            for ($i = $currentCount + 1; $i <= 200; $i++) {
                $extra[] = [
                    'name' => 'Participant ' . $i,
                    'email' => 'participant' . $i . '@pup.edu.ph',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Participant::insert($extra);
        }
        $allParticipantIds = Participant::pluck('id');

        // 3. Seed initial custom events (with Week 11 required columns & rare keywords)
        $e1 = Event::create([
            'title' => 'BSIT Tech Symposium 2026',
            'description' => 'This is a rare keynote seminar focusing on quantum computing architectures.',
            'details' => json_encode(['dress_code' => 'Formal', 'capacity' => 200, 'tags' => ['tech'], 'special_guest' => 'Astronaut']),
            'start_time' => now()->addDays(5),
            'venue_id' => $v1->id
        ]);

        $e2 = Event::create([
            'title' => 'Campus Career Fair',
            'description' => 'Standard career fair for students.',
            'details' => json_encode(['dress_code' => 'Business', 'capacity' => 150, 'tags' => ['career'], 'special_guest' => 'Recruiter']),
            'start_time' => now()->addDays(10),
            'venue_id' => $v1->id
        ]);

        // 4. Grow Data: Seed the rest up to 20,000 events in chunks of 1,000
        $totalEvents = 20000;
        $chunkSize = 1000;
        $this->command->info("Seeding remaining events in chunks...");

        for ($i = 0; $i < $totalEvents; $i += $chunkSize) {
            $eventsBatch = [];

            for ($j = 0; $j < $chunkSize; $j++) {
                $index = $i + $j;
                $venue = $venues[$index % count($venues)];

                $eventsBatch[] = [
                    'title' => 'Campus Event #' . ($index + 1),
                    'description' => 'Standard campus workshop discussing general web development.',
                    'details' => json_encode([
                        'dress_code' => 'Smart Casual',
                        'capacity' => 100,
                        'tags' => ['tech', 'workshop'],
                        'special_guest' => 'Faculty Member',
                    ]),
                    'venue_id' => $venue->id,
                    'start_time' => now()->addDays(rand(1, 90)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Event::insert($eventsBatch);
        }

        // 5. Registrations for the first 100 events
        $firstEvents = Event::take(100)->get();
        foreach ($firstEvents as $event) {
            $assigned = $allParticipantIds->random(rand(3, 8));
            $attachData = [];
            foreach ($assigned as $pId) {
                $attachData[$pId] = ['status' => (rand(0, 1) === 1) ? 'registered' : 'waitlisted'];
            }
            $event->participants()->syncWithoutDetaching($attachData);
        }

        // 6. Notes
        $e1->notes()->create(['body' => 'Keynote speaker confirmed for BSIT opening session.']);
        $v1->notes()->create(['body' => 'Maximum seating capacity is 500 participants.']);

        $this->command->info('Seeding completed successfully!');
    }
}