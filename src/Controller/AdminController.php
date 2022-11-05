<?php

namespace App\Controller;

use App\Model\ArticleManager;

class AdminController extends AbstractController
{
    /**
     * Display admin page.
     */
    public function admin(): string
    {
        return $this->twig->render('Admin/admin.html.twig');
    }

    public function seeArticles(): string
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll('id');

        return $this->twig->render('Admin/see-articles.html.twig', ['articles' => $articles]);
    }
}
