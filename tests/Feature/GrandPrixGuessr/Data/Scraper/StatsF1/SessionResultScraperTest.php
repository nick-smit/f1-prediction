<?php

declare(strict_types=1);

namespace Tests\Feature\GrandPrixGuessr\Data\Scraper\StatsF1;

use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultNotFoundException;
use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultScraper;
use App\GrandPrixGuessr\Session\SessionType;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class SessionResultScraperTest extends TestCase
{
    public function test_a_qualification_result_can_be_scraped(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://www.statsf1.com/en/2024/italy/qualification.aspx' => $this->getResponse('2024-italy-qualification')
        ]);

        $crawler = $this->app->make(SessionResultScraper::class);

        $result = $crawler->scrape(2024, 'italy', SessionType::Qualification);

        $this->assertCount(20, $result);
        $this->assertSame([
            'Lando Norris',
            'Oscar Piastri',
            'George Russell',
            'Charles Leclerc',
            'Carlos Sainz',
            'Lewis Hamilton',
            'Max Verstappen',
            'Sergio Perez',
            'Alexander Albon',
            'Nico Hulkenberg',
            'Fernando Alonso',
            'Daniel Ricciardo',
            'Kevin Magnussen',
            'Pierre Gasly',
            'Esteban Ocon',
            'Yuki Tsunoda',
            'Lance Stroll',
            'Franco Colapinto',
            'Valtteri Bottas',
            'Guanyu Zhou',
        ], $result->toArray());
    }

    public function test_a_race_result_can_be_scraped(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://www.statsf1.com/en/2024/azerbaidjan/classement.aspx' => $this->getResponse('2024-azerbaidjan-race')
        ]);

        $crawler = $this->app->make(SessionResultScraper::class);

        $result = $crawler->scrape(2024, 'azerbaidjan', SessionType::Race);

        $this->assertCount(20, $result);
        $this->assertSame([
            'Oscar Piastri',
            'Charles Leclerc',
            'George Russell',
            'Lando Norris',
            'Max Verstappen',
            'Fernando Alonso',
            'Alexander Albon',
            'Franco Colapinto',
            'Lewis Hamilton',
            'Oliver Bearman',
            'Nico Hulkenberg',
            'Pierre Gasly',
            'Daniel Ricciardo',
            'Guanyu Zhou',
            'Esteban Ocon',
            'Valtteri Bottas',
            'Sergio Perez',
            'Carlos Sainz',
            'Lance Stroll',
            'Yuki Tsunoda',
        ], $result->toArray());
    }

    public function test_the_result_of_a_qualification_session_could_not_be_found(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://www.statsf1.com/en/2024/singapour/qualification.aspx' => $this->getResponse('2024-singapour-qualification')
        ]);

        $crawler = $this->app->make(SessionResultScraper::class);

        $this->expectException(SessionResultNotFoundException::class);
        $crawler->scrape(2024, 'singapour', SessionType::Qualification);
    }

    public function test_the_result_of_a_qualification_session_where_a_driver_didnt_start(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://www.statsf1.com/en/2023/sao-paulo/qualification.aspx' => $this->getResponse('2023-sao-paulo-qualification')
        ]);

        $crawler = $this->app->make(SessionResultScraper::class);

        $result = $crawler->scrape(2023, 'sao-paulo', SessionType::Qualification);

        $this->assertCount(20, $result);
        $this->assertSame([
            'Max Verstappen',
            'Charles Leclerc',
            'Lance Stroll',
            'Fernando Alonso',
            'Lewis Hamilton',
            'George Russell',
            'Lando Norris',
            'Carlos Sainz',
            'Sergio Perez',
            'Oscar Piastri',
            'Nico Hulkenberg',
            'Esteban Ocon',
            'Pierre Gasly',
            'Kevin Magnussen',
            'Alexander Albon',
            'Yuki Tsunoda',
            'Daniel Ricciardo',
            'Valtteri Bottas',
            'Logan Sargeant',
            'Guanyu Zhou',
        ], $result->toArray());
    }

    public function test_the_result_of_a_qualification_session_where_a_driver_is_disqualified(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://www.statsf1.com/en/2024/azerbaidjan/qualification.aspx' => $this->getResponse('2024-azerbaidjan-qualification')
        ]);

        $crawler = $this->app->make(SessionResultScraper::class);

        $result = $crawler->scrape(2024, 'azerbaidjan', SessionType::Qualification);

        $this->assertCount(19, $result);
        $this->assertSame([
            'Charles Leclerc',
            'Oscar Piastri',
            'Carlos Sainz',
            'Sergio Perez',
            'George Russell',
            'Max Verstappen',
            'Lewis Hamilton',
            'Fernando Alonso',
            'Franco Colapinto',
            'Alexander Albon',
            'Oliver Bearman',
            'Yuki Tsunoda',
            'Nico Hulkenberg',
            'Lance Stroll',
            'Daniel Ricciardo',
            'Lando Norris',
            'Valtteri Bottas',
            'Guanyu Zhou',
            'Esteban Ocon',

        ], $result->toArray());
    }

    public function test_the_result_of_a_qualification_session_where_multiple_drives_are_disqualified(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://www.statsf1.com/en/2024/monaco/qualification.aspx' => $this->getResponse('2024-monaco-qualification')
        ]);

        $crawler = $this->app->make(SessionResultScraper::class);

        $result = $crawler->scrape(2024, 'monaco', SessionType::Qualification);

        $this->assertCount(18, $result);
        $this->assertSame([
            'Charles Leclerc',
            'Oscar Piastri',
            'Carlos Sainz',
            'Lando Norris',
            'George Russell',
            'Max Verstappen',
            'Lewis Hamilton',
            'Yuki Tsunoda',
            'Alexander Albon',
            'Pierre Gasly',
            'Esteban Ocon',
            'Daniel Ricciardo',
            'Lance Stroll',
            'Fernando Alonso',
            'Logan Sargeant',
            'Sergio Perez',
            'Valtteri Bottas',
            'Guanyu Zhou',

        ], $result->toArray());
    }

    public function test_the_result_of_a_race_session_where_a_driver_is_disqualified(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://www.statsf1.com/en/2024/belgique/classement.aspx' => $this->getResponse('2024-belgique-race')
        ]);

        $crawler = $this->app->make(SessionResultScraper::class);

        $result = $crawler->scrape(2024, 'belgique', SessionType::Race);

        $this->assertCount(19, $result);
        $this->assertSame([
            'Lewis Hamilton',
            'Oscar Piastri',
            'Charles Leclerc',
            'Max Verstappen',
            'Lando Norris',
            'Carlos Sainz',
            'Sergio Perez',
            'Fernando Alonso',
            'Esteban Ocon',
            'Daniel Ricciardo',
            'Lance Stroll',
            'Alexander Albon',
            'Pierre Gasly',
            'Kevin Magnussen',
            'Valtteri Bottas',
            'Yuki Tsunoda',
            'Logan Sargeant',
            'Nico Hulkenberg',
            'Guanyu Zhou',
        ], $result->toArray());
    }

    private function getResponse(string $filename): PromiseInterface
    {
        return Http::response(Storage::get('grand-prix-guessr/data/scraper/stats-f1/' . $filename . '.html'));
    }
}
