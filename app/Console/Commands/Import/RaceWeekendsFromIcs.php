<?php

declare(strict_types=1);

namespace App\Console\Commands\Import;

use App\GrandPrixGuessr\Session\SessionType;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use DateTime;
use ICal\Event;
use ICal\ICal;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\RequestException;

class RaceWeekendsFromIcs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:race-weekends-from-ics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves an ICS file from motorsportcalendars.com and imports that into the database';

    /**
     * Execute the console command.
     * @throws RequestException
     */
    public function handle(Factory $httpClient): int
    {
        $this->info("Fetching ics file from motorsportcalendars.com");
        $fileContent = $this->fetchIcsFile($httpClient);

        $structuredEvents = $this->icsToStructuredEvents($fileContent);

        $importedSessions = 0;
        $this->info(sprintf('Importing %d race weekends', count($structuredEvents)));
        foreach ($structuredEvents as $eventName => $events) {
            $raceWeekend = new RaceWeekend([
                'name' => $eventName,
                'start_date' => new DateTime($events[0]->dtstart_tz),
            ]);

            $raceWeekend->save();

            foreach ($events as $event) {
                ++$importedSessions;
                $session = new RaceSession([
                    'session_start' => $event->dtstart_tz,
                    'session_end' => $event->dtend_tz,
                    'type' => match ($event->additionalProperties['categories_array'][1]) {
                        'FP1,F1', 'FP2,F1', 'FP3,F1' => SessionType::Practice,
                        'Qualifying,F1' => SessionType::Qualification,
                        'Grand Prix,F1' => SessionType::Race,
                        'Sprint Qualifying,F1' => SessionType::SprintQualification,
                        'Sprint,F1' => SessionType::SprintRace,
                    }
                ]);

                $session->guessable = $session->type === SessionType::Qualification || $session->type === SessionType::Race;

                $raceWeekend->raceSessions()->save($session);
            }
        }

        $this->info(sprintf('Imported %d race sessions', $importedSessions));

        return self::SUCCESS;
    }

    /**
     * @throws RequestException
     */
    private function fetchIcsFile(Factory $httpClient): string
    {
        $url = 'https://files-f1.motorsportcalendars.com/f1-calendar_p1_p2_p3_qualifying_sprint_gp.ics';
        $response = $httpClient->get($url);
        $response->throw();

        return $response->body();
    }

    /**
     * @return Event[][]
     */
    private function icsToStructuredEvents(string $fileContent): array
    {
        $structuredEvents = [];

        $ICal = (new ICal())->initString($fileContent);
        /** @var Event $event */
        foreach ($ICal->events() as $event) {
            $matches = [];
            preg_match('/\(([^\)]+)/', $event->summary, $matches);
            $structuredEvents[substr($matches[0], 1)][] = $event;
        }

        return $structuredEvents;
    }
}
