<?php

namespace App\Controller;

 use JasonGrimes\Paginator;

use App\Model\ArticleManager;
use App\Model\CommentsManager;
use App\Model\UserManager;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Anime\AnimeRequest;
use Jikan\Request\Anime\AnimeVideosRequest;
use Jikan\Request\Search\AnimeSearchRequest;
use Jikan\Request\Top\TopAnimeRequest;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class AnimeController extends AbstractController
{
    /**
     * List animes.
     */
    public function listAnime(): string
    {
        if (isset($_SESSION['user_id'])) {
            $userManager = new UserManager();
            $user_profile = $userManager->selectOneById($_SESSION['user_id']);
        } else {
            $user_profile = '';
        }

        $apiAnime = new MalClient();
        $topAnime = $apiAnime->getTopAnime(new TopAnimeRequest(1, 'tv'));
        $result = $topAnime->getResults();


$totalItems = 1000;
$itemsPerPage = 50;
$currentPage = 8;
$urlPattern = '/foo/page/(:num)';

$paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);


        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll('title');

        return $this->twig->render('Anime/anime.html.twig', ['anime_list' => $result,
            'article' => $articles,
        'pagination' => $paginator
        ]);
    }

    // view anime info

    public function showAnimeMoreInfo(int $malId): string
    {
        if (isset($_SESSION['id'])) {
            $userManager = new UserManager();
            $user_profile = $userManager->selectOneById($_SESSION['id']);
        } else {
            $user_profile = 'end';
        }

        $apiAnime = new MalClient();

        $data = $apiAnime->getAnime(new AnimeRequest($malId));

        $videos = $apiAnime->getAnimeVideos(
            new AnimeVideosRequest($malId)
        );

        // Streamable Episodes
        $episodes = $videos->getEpisodes();

        $commentManager = new CommentsManager();
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $userComment = $_POST['user_comment'];

            $commentManager->addComment($userComment);
            header('Location: /anime/show?id='.$malId);
        }

        $comments = $commentManager->selectAll('id');

        return $this->twig->render('Anime/show.html.twig', ['anime_show' => $data,
            'episode' => $episodes,
            'comments' => $comments,
        ]);
    }

    //  search anime
     public function searchAnime(string $query): ?string
     {
         if (isset($_SESSION['id'])) {
             $userManager = new UserManager();
             $user_profile = $userManager->selectOneById($_SESSION['id']);
         } else {
             $user_profile = 'end';
         }

         if ('POST' === $_SERVER['REQUEST_METHOD']) {
             $query = array_map('trim', $_POST);
         }

         $apiAnime = new MalClient();

         $animeSearchResults = $apiAnime->getAnimeSearch(new AnimeSearchRequest((string) $query));

         return $this->twig->render('Anime/search.html.twig', ['anime_search' => $animeSearchResults,
             'found' => $query,
         ]);
     }
}
