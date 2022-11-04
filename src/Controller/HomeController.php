<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\Cookie;
use App\Model\Session;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Top\TopAnimeRequest;
use Jikan\Request\Top\TopMangaRequest;

class HomeController extends AbstractController
{
    /**
     * Display home page.
     */
    public function index(): string
    {
        $session = new Session();
        $session->read('mail');

        $api = new MalClient();
        $topManga = $api->getTopManga(new TopMangaRequest(1, 'manga'));
        $manga = $topManga->getResults();

        $topAnime = $api->getTopAnime(new TopAnimeRequest(1, 'tv'));
        $anime = $topAnime->getResults();

        $articleManager = new ArticleManager();
        $article = $articleManager->selectAll('title');

        return $this->twig->render('Home/index.html.twig', ['manga_list' => $manga,
            'anime_list' => $anime,
            'article' => $article,
            'session' => $_SESSION,
        ]);
    }

    public function likeAnime($id)
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $cookie = new Cookie();
            $cookie->setCookie('anime_like', $id);

            header('Location: /');
        }
    }

        public function likeManga($id)
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $cookie = new Cookie();
                $cookie->setCookie('manga_like', $id);

                header('Location: /');
            }
        }
}
