<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\Breadcrumb;
use App\Model\Session;
use App\Model\UserManager;
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
        $breadcrumb = new Breadcrumb();
        $breadcrumbMake = $breadcrumb->makeBreadCrumbs();

        $session = new Session();
        $id = $session->read('id');

        $active = $_SERVER['PHP_SELF'];

        if (isset($_SESSION['id'])) {
            $userManager = new UserManager();
            $user_profile = $userManager->selectOneById($_SESSION['id']);
        } else {
            $user_profile = 'end';
        }

        $apiManga = new MalClient();
        $topManga = $apiManga->getTopManga(new TopMangaRequest(1, 'manga'));
        $manga = $topManga->getResults();

        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll('title');

        return $this->twig->render('Manga/manga.html.twig', ['manga_list' => $manga,
            'article' => $articles,
            'session' => $_SESSION,
            'user' => $user_profile,
            'breadcrumb' => $breadcrumbMake,
            'active' => $active,
        ]);
    }

    // show unique page info manga
         public function showMangaMoreInfo(int $malId): string
         {
             $breadcrumb = new Breadcrumb();
             $breadcrumbMake = $breadcrumb->makeBreadCrumbs();

             $session = new Session();
             $id = $session->read('id');
             $active = $_SERVER['PHP_SELF'];
             if (isset($_SESSION['id'])) {
                 $userManager = new UserManager();
                 $user_profile = $userManager->selectOneById($_SESSION['id']);
             } else {
                 $user_profile = 'en';
             }

             $apiAnime = new MalClient();

             $data = $apiAnime->getManga(new MangaRequest($malId));

             return $this->twig->render('Manga/show.html.twig', ['manga_show' => $data,
                 'session' => $_SESSION,
                 'user' => $user_profile,
                 'breadcrumb' => $breadcrumbMake,
                 'active' => $active,
             ]);
         }
}
