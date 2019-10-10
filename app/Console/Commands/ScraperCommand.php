<?php

namespace App\Console\Commands;

use App\Comic;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class ScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scapes comic info and stuff';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$last = $this->getLastComicId();
        $comic = $this->getComicInfo(1);
        $comic->save();
    }
    public function getComicInfo($id):Comic {
        $guzzle = new Client();
        $url = 'https://xkcd.com/' . $id . '/';
        $resp = $guzzle->get($url);
        $html = $resp->getBody()->getContents();
        $crawler = new Crawler($html);
        $titleEl = $crawler->filter('div#ctitle');
        $comic = new Comic();
        $comic->external_id = $id;
        $comic->url = $url;
        $comic->title = $titleEl->text();
        $imgEl = $crawler->filter('div#comic img');
        $comic->img = $imgEl->attr('src');
        $comic->alt = $imgEl->attr('title');
        return $comic;
    }
    public function getLastComicId(){
        $guzzle = new Client();
        $resp = $guzzle->get('https://xkcd.com');
        $html = $resp->getBody()->getContents();
        $crawler = new Crawler($html);
        $permUrlEl = $crawler->filter('meta[property="og:url"]');
        $permUrl = $permUrlEl->attr('content');
        $parts = explode('/', $permUrl);
        return (int) $parts[3];
    }
}
