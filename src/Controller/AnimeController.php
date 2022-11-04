<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\CommentsManager;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Anime\AnimeNewsRequest;
use Jikan\Request\Anime\AnimeRequest;
use Jikan\Request\Anime\AnimeVideosRequest;
use Jikan\Request\Search\AnimeSearchRequest;
use Jikan\Request\Top\TopAnimeRequest;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

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

        $adapter = new ArrayAdapter($result);
        $pagerfanta = new Pagerfanta($adapter);

        $maxPerPage = $pagerfanta->getMaxPerPage(5);
        $pagerfanta->setMaxPerPage($maxPerPage); // 10 by default

        $currentPage = $pagerfanta->getCurrentPage();
        $pagerfanta->setCurrentPage($currentPage); // 1 by default

        $nbResults = $pagerfanta->getNbResults();
        $currentPageResults = $pagerfanta->getCurrentPageResults();

        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll('title');

        return $this->twig->render('Anime/anime.html.twig', ['anime_list' => $result,
            'totalPerPage' => $currentPageResults,
            'total' => $nbResults,
            'pager' => $pagerfanta,
            'article' => $articles,
        ]);
    }

     public function showAnimeMoreInfo(int $malId): string
     {
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

     public function searchAnime($query): ?string
     {
         $apiAnime = new MalClient();

         $animeSearchResults = $apiAnime->getAnimeSearch(new AnimeSearchRequest((string) $query));

         return $this->twig->render('Anime/search.html.twig', ['anime_search' => $animeSearchResults,
             'found' => $query, ]);
     }

     public function animeNews(): string
     {
         $apiAnime = new MalClient();

         $data = $apiAnime->getNewsList(new AnimeNewsRequest(5114, 1));

         return $this->twig->render('Anime/anime.html.twig', ['news' => $data]);
     }
}
