<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\Data\Scraper\StatsF1;

use App\GrandPrixGuessr\Session\SessionType;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class SessionResultScraper
{
    private readonly PendingRequest $client;

    public function __construct(Factory $factory)
    {
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->client = $factory->baseUrl('https://www.statsf1.com')
            ->throw();
    }

    public function scrape(int $year, string $statsF1Name, SessionType $sessionType): Collection
    {
        $resultPage = match ($sessionType) {
            SessionType::Qualification => 'qualification.aspx',
            SessionType::Race => 'classement.aspx',
        };

        $url = $this->buildUrl($year, $statsF1Name, $resultPage);
        $resultBody = $this->client->get($url)->body();

        $domCrawler = new Crawler($resultBody);
        $table = $domCrawler->filter('#ctl00_CPH_Main_GV_Stats>tbody tr');

        if ($table->count() === 0) {
            throw new SessionResultNotFoundException('Session results were not found.');
        }

        $rowIndex = match ($sessionType) {
            SessionType::Qualification => 2,
            SessionType::Race => 3,
        };

        $output = new Collection();
        foreach ($table as $row) {
            if ($sessionType === SessionType::Race && $row->childNodes->item(1)->nodeValue === 'dsq') {
                continue;
            }

            $driverName = $row->childNodes->item($rowIndex)->nodeValue;
            $driverNameSanitized = $this->sanitizeDriverName($driverName);
            $driverNameNormalized = $this->normalizeDriverName($driverNameSanitized);

            $output->push($driverNameNormalized);
        }

        if ($sessionType === SessionType::Qualification) {
            return $this->removeDisqualifiedDrivers($domCrawler, $output);
        }

        return $output;
    }

    private function buildUrl(int $year, string $statsF1Name, string $resultPage): string
    {
        return implode('/', ['en', $year, $statsF1Name, $resultPage]);
    }

    private function sanitizeDriverName(string $driverName): string
    {
        $driverName = preg_replace('/[^a-zA-Z\s]/', '', $driverName);

        return trim((string) $driverName);
    }

    private function normalizeDriverName(string $driverName): string
    {
        return Str::title($driverName);
    }

    private function removeDisqualifiedDrivers(Crawler $domCrawler, Collection $output): Collection
    {
        $comments = $domCrawler->filter('#ctl00_CPH_Main_P_Commentaire');
        $commentsArray = $this->normalizeQualificationComments($comments);

        foreach ($commentsArray as $comment) {
            if (str_contains((string) $comment, 'disqualified')) {
                $disqualifiedDrivers = $output->where(function (string $driverName) use ($comment): bool {
                    [$firstname, $lastname] = explode(' ', $driverName);

                    $parsedName = $firstname[0] . '. ' . Str::ucfirst(strtolower($lastname));
                    return str_contains(Str::transliterate($comment), $parsedName);
                });

                $output->forget($disqualifiedDrivers->keys());
            }
        }

        return $output->values();
    }

    private function normalizeQualificationComments(Crawler $comments): array
    {
        $commentsString = trim($comments->html());
        $withNewlines = preg_replace('/<br\s?\/?>/i', "\n", $commentsString);
        return explode("\n", (string) $withNewlines);
    }
}
