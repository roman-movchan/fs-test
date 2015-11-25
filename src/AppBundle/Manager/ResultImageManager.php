<?php


namespace AppBundle\Manager;
use Symfony\Component\DomCrawler\Crawler;
use Guzzle\Http\Client;

class ResultImageManager
{
    public function getRandomImage()
    {
        $client = new Client('http://gifbin.com/');
        $request = $client->get('random');
        $response = $request->send();
        $crawler = new Crawler($response->getBody(true));
        $res = $crawler->filter('form#share-form > fieldset')->eq('2')->filter('input')->attr('value');

        return $res;
    }
}