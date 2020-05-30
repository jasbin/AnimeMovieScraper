<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpClient\HttpClient;
class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //store anime details : title,link,image
        $animeList = [];

        //set http client with user-agent to ignore bots check
        $client = new Client(HttpClient::create(array(
            'headers' => array(
                'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Referer' => 'http://gogoanime.io/',
                'Upgrade-Insecure-Requests' => '1',
                'Save-Data' => 'on',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
            ),
        )));

        $crawler = $client->request('GET', 'https://gogoanime.io/');
        $crawler->filter('.last_episodes > .items > li > .img')->each(function ($node) use (&$animeList){
            $link = ['link'=>'http://gogoanime.io'.$node->filter('a')->attr('href')];
            $title = ['title' => $node->filter('a')->attr('title')];
            $image = ['image' => $node->filter('img')->attr('src')];
            //add all array to animeList array
            $animeList[] = [$title,$link,$image];
        });

        return response()->json([
            'animeList' => $animeList
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getm3u8ByLink(Request $request){
        if(!empty($request)){
           return generateLiveLink($request->episode);
        }
        return "no url provided";
    }

    public function episodeList(Request $request){
        $client = new Client(HttpClient::create(array(
            'headers' => array(
                'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Referer' => 'http://gogoanime.io/',
                'Upgrade-Insecure-Requests' => '1',
                'Save-Data' => 'on',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
            ),
        )));

        $totalEpisode = 0;
        $url = str_replace("\\","",$request->episode);
        $crawler = $client->request('GET', $url);
        $crawler->filter('#episode_page > li > a')->last()->each(function ($node) use (&$totalEpisode){
            $totalEpisode = $node->attr('ep_end');
        });

        $episodeList = [];
        preg_match("/http(?:.*)episode-/",$url,$matches);
        $tempLink = $matches[0];
        for($i=1; $i<=$totalEpisode; $i++)
            $episodeList[] = $tempLink.$i;

        return response()->json([
            'totalEpisode' => $totalEpisode,
            'episode_list' => $episodeList
        ],200);
    }

    public function search(Request $request){
        $client = new Client(HttpClient::create(array(
                'headers' => array(
                    'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Referer' => 'http://gogoanime.io/',
                    'Upgrade-Insecure-Requests' => '1',
                    'Save-Data' => 'on',
                    'Pragma' => 'no-cache',
                    'Cache-Control' => 'no-cache',
                    ),
        )));

        $animeList = [];
        $crawler = $client->request('GET', 'https://www19.gogoanime.io//search.html?keyword='.$request->keyword);
                $crawler->filter('.last_episodes > ul > li')->each(function ($node) use (&$animeList){
                    $name = ['name' => $node->filter('.name')->text()];
                    $link = ['link' => 'http://gogoanime.io/'.$node->filter('div>a')->attr('href')];
                    $released = ['released' => $node->filter('.released')->text()];
                    $animeList[] = [$name,$link,$released];
                });

        return response()->json($animeList);
    }
}
