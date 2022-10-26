<?php

namespace App\Controller;

use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Top\TopMangaRequest;

class MangaController extends AbstractController
{
    /**
     * List items.
     */
    public function topManga(): string
    {
        $apiManga = new MalClient();
        $topManga = $apiManga->getTopManga(new TopMangaRequest(1, 'manga'));
        $result = $topManga->getResults();

        return $this->twig->render('Manga/manga.html.twig', ['result' => $result]);
    }
}
