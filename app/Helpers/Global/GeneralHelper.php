<?php

use Goutte\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpClient\HttpClient;

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend')) {
                return 'admin.dashboard';
            }

            return 'frontend.user.dashboard';
        }

        return 'frontend.index';
    }
}

function generateLiveLink($link=null){
    // dd($request->all());

    if($link != null){

            $client = new Client(HttpClient::create(array(
                'headers' => array(
                    'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Referer' => 'http://vidstreaming.io/',
                    'Upgrade-Insecure-Requests' => '1',
                    'Save-Data' => 'on',
                    'Pragma' => 'no-cache',
                    'Cache-Control' => 'no-cache',
                ),
            )));
            //gives m3u8 link from anime url when ifram containing url is passed
            $crawler = $client->request('GET', $link);
            $vidLink = '';
            $crawler->filter('.play-video > iframe')->each(function ($node) use (&$vidLink){
                $vidLink = $node->attr('src');
            });

            if($vidLink === '')
                return response()->json(['error'=>'invalid url provided'],404);

            //replace double slash only with https://
            $vidLink = str_replace("//","https://",$vidLink);

            //get the m3u8 link
            $crawler = $client->request('GET', $vidLink );

            //regix generated using the help of http://buildregex.com/
            preg_match("/https(?:.*)m3u8/",$crawler->html(),$matches);
            $m3u8Link = $matches[0];
            return response()->json([
                'm3u8Link' => $m3u8Link
            ],200);

    }
    return response()->json([
        'error' => 'no url provided'
    ], 404);
}
