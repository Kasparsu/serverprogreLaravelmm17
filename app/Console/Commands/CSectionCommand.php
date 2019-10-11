<?php

namespace App\Console\Commands;

use App\Comic;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class CSectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:csection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $url = 'https://www.csectioncomics.com';
        $html = $this->getPageHtml($url);
        $data = $this->getComicData($html);
        $url = $this->getNextUrl($html);
        $data->save();
        while(true){
            $html = $this->getPageHtml($url);
            $data = $this->getComicData($html, $url);
            $url = $this->getNextUrl($html);
            $data->save();
        }
    }
    public function getPageHtml($url) {
        $guzzle = new Client();
        $resp = $guzzle->get($url);
        return $resp->getBody()->getContents();
    }
    public function getNextUrl($html){
        $crawler = new Crawler($html);
        $prevLinkEl = $crawler->filter('#sidebar-over-comic a.navi-prev');
        return $prevLinkEl->attr('href');
    }

    public function getComicData($html, $url=null) {
        $comic = new Comic();
        $crawler = new Crawler($html);
        $imgEl = $crawler->filter('#comic>img');
        $comic->img = $imgEl->attr('src');
        $comic->alt = $imgEl->attr('title');
        if(!$url) {
            $titleEl = $crawler->filter('h2.entry-title>a');
            $comic->title = $titleEl->text();
            $comic->url = $titleEl->attr('href');
        } else {
            $titleEl = $crawler->filter('h1.entry-title');
            $comic->title = $titleEl->text();
            $comic->url = $url;
        }
        return $comic;
    }
}
