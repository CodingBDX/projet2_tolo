<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\Cookie;
use App\Model\UserManager;
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
        if (isset($_SESSION['user_id'])) {
            // $session->read('user_id');

            $userManager = new UserManager();
            $user_profile = $userManager->selectOneById($_SESSION['user_id']);
        } else {
            $user_profile = '';
        }
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
        ]);
    }

        public function likeManga($id)
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $cookie = new Cookie();
                $cookie->setCookie('manga_like', $id);

                header('Location: /');
            }
        }

         public function likeAnime($id)
         {
             if ('POST' === $_SERVER['REQUEST_METHOD']) {
                 $cookie = new Cookie();
                 $cookie->setCookie('anime_like', $id);

                 header('Location: /');
             }
         }
}
