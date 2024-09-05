<?php

declare(strict_types=1);

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Factory;

class Formula1ComScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:formula1-com-schedule {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes the formula1 schedule from formula1.com';

    /**
     * Execute the console command.
     */
    public function handle(Factory $httpFactory): never
    {
        $response = $httpFactory->get('formula1.com/en/racing/' . $this->argument('year'));

        dd($response->body());
    }
}
