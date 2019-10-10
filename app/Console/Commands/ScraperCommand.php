<?php

namespace App\Console\Commands;

use App\Comic;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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
        $last = $this->getLastComicId();
        $bar = $this->output->createProgressBar($last);
        $bar->start();
        for($i=1;$i<=$last; $i++) {
            try {
                $comic = $this->getComicInfo($i);
                $comic->save();
            } catch (\Exception $e){

            }
            $bar->advance();
        }
        $bar->finish();
    }
    public function getHtml($url){
        if(Cache::has($url)){
            return Cache::get($url);
        } else {
            $guzzle = new Client();
            $resp = $guzzle->get($url);
            var_dump("get url");
            $html = $resp->getBody()->getContents();
            Cache::put($url, $html);
            return $html;
        }
    }
    public function getComicInfo($id):Comic {

        $url = 'https://xkcd.com/' . $id . '/';
        $html = $this->getHtml($url);
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
        $html = $this->getHtml('https://xkcd.com');
        $crawler = new Crawler($html);
        $permUrlEl = $crawler->filter('meta[property="og:url"]');
        $permUrl = $permUrlEl->attr('content');
        $parts = explode('/', $permUrl);
        return (int) $parts[3];
    }
}
