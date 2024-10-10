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
use Laravel\Prompts\TextPrompt;

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
            /** @var RaceWeekend $previousRaceWeekend */
            $previousRaceWeekend = RaceWeekend::whereName($eventName)->first();
            $statsF1Name = $previousRaceWeekend?->stats_f1_name;
            if ($statsF1Name === null) {
                $statsF1Url = new TextPrompt(
                    'What is the stats f1 url for the ' . $eventName,
                    required: true,
                    transform: function (string $value): string {
                        // @codeCoverageIgnoreStart
                        $parts = explode('/', $value);
                        return substr(end($parts), 0, -strlen('.aspx'));
                        // @codeCoverageIgnoreEnd
                    },
                );

                $statsF1Name = $statsF1Url->prompt();
                $this->info(sprintf('Using name %s', $statsF1Name));
            }

            /** @var RaceWeekend $raceWeekend */
            $raceWeekend = RaceWeekend::whereName($eventName)
                ->whereRaw('YEAR(start_date) = ?', [(new DateTime($events[0]->dtstart_tz))->format('Y')])
                ->firstOrCreate(
                    [
                        'name' => $eventName,
                        'start_date' => new DateTime($events[0]->dtstart_tz),
                        'stats_f1_name' => $statsF1Name,
                    ]
                );

            $raceWeekend->save();

            foreach ($events as $event) {
                $eventType = match ($event->additionalProperties['categories_array'][1]) {
                    'Qualifying,F1' => SessionType::Qualification,
                    'Grand Prix,F1' => SessionType::Race,
                    default => null,
                };

                if ($eventType === null) {
                    continue;
                }

                ++$importedSessions;
                $session = new RaceSession([
                    'session_start' => $event->dtstart_tz,
                    'session_end' => $event->dtend_tz,
                    'type' => $eventType
                ]);

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
