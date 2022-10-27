<?php

namespace App\Controller;

use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Manga\MangaRequest;
use Jikan\Request\Top\TopMangaRequest;

class MangaController extends AbstractController
{
    /**
     * List items.
     */
    public function listManga(): string
    {
        $apiManga = new MalClient();
        $topManga = $apiManga->getTopManga(new TopMangaRequest(1, 'manga'));
        $manga = $topManga->getResults();

        return $this->twig->render('Manga/manga.html.twig', ['manga_list' => $manga]);
    }

         public function showMangaMoreInfo(int $malId): string
         {
             $apiAnime = new MalClient();

             $data = $apiAnime->getManga(new MangaRequest($malId));

             return $this->twig->render('Manga/show.html.twig', ['manga_show' => $data]);
         }
}
