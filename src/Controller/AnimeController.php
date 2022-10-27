<?php

namespace App\Controller;

use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Anime\AnimeNewsRequest;
use Jikan\Request\Anime\AnimeRequest;
use Jikan\Request\Top\TopAnimeRequest;

class AnimeController extends AbstractController
{
    /**
     * List items.
     */
    public function listAnime(): string
    {
        $apiAnime = new MalClient();
        $topAnime = $apiAnime->getTopAnime(new TopAnimeRequest(1, 'tv'));
        $result = $topAnime->getResults();

        return $this->twig->render('Anime/anime.html.twig', ['anime_list' => $result]);
    }

     public function showAnimeMoreInfo(int $malId): string
     {
         $apiAnime = new MalClient();

         $data = $apiAnime->getAnime(new AnimeRequest($malId));

         return $this->twig->render('Anime/show.html.twig', ['anime_show' => $data]);
     }

     public function animeNews(): string
     {
         $apiAnime = new MalClient();

         $data = $apiAnime->getNewsList(new AnimeNewsRequest(5114, 1));

         return $this->twig->render('Anime/anime.html.twig', ['news' => $data]);
     }
}
