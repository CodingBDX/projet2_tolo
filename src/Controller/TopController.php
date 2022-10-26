<?php

namespace App\Controller;

class TopController extends AbstractController
{
    /**
     * List items.
     */
    public function getTop(): string
    {
        $manga = new MangaController();
        // $anime = new AnimeController();
        $getManga = $manga->topManga();
        // $getAnime = $anime->topAnime();

        return $this->twig->render('_Component/_top.html.twig', [
            'manga' => $getManga, ]);
    }
}
