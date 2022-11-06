<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\Breadcrumb;
use App\Model\Cookie;
use App\Model\Session;
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
        $breadcrumb = new Breadcrumb();
        $breadcrumbMake = $breadcrumb->makeBreadCrumbs();

        $session = new Session();
        $id = $session->read('user_id');

        $active = $_SERVER['PHP_SELF'];

        if (isset($_SESSION['user_id'])) {
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
            'session' => $_SESSION,
            'user' => $user_profile,
            'breadcrumb' => $breadcrumbMake,
            'active' => $active,
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
