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
            // $m3u8 = generateLiveLink('http://gogoanime.io'.$node->filter('a')->attr('href'));
            // $m3u8Link = ['m3u8Link' => $m3u8 ];
            //add all array to animeList array
            $animeList[] = [$title,$link,$image];
        }
    );

    //loop through all anime details
    // foreach($animeList as $key => $value){
    //     foreach($value as $k=>$val)
    //         {
    //             foreach($val as $v)
    //                 print_r($v);
    //             print_r("</br>");
    //         }
    //     print_r('-----------------------------------------'."</br>");
    // }

        // $m3u8Link = $this->generateLiveLink("https://www19.gogoanime.io/onikirimaru-episode-4");
        // print 'm3u8 link: '.$m3u8Link;

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
}
