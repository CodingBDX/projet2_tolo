<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\Session;
use App\Model\UserManager;

class ArticlesController extends AbstractController
{
    public function addArticle(): ?string
    {
        // TODO validations (length, format...)
        $errors = [];

        $session = new Session();
        $id = $session->read('id');

        if (isset($_SESSION['id'])) {
            $userManager = new UserManager();
            $user_profile = $userManager->selectOneById($_SESSION['id']);
        } else {
            $user_profile = '';
        }

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $item = array_map('trim', $_POST);

            $title = trim($_POST['article-title']);
            $content = trim($_POST['article-content']);
            $author = trim($_POST['article-author']);
            $url = trim($_POST['article-picture']);
            if (isset($_POST['article-featured'])) {
                $featured = trim($_POST['article-featured']);
            } else {
                $featured = false;
            }

            if (!isset($title) || (empty(trim($title)))) {
                $errors[] = 'Titre incorrect';
            }
            if (!isset($content) || (empty(trim($content)))) {
                $errors[] = 'Content incorrect';
            }
            if (!isset($author) || (empty(trim($author)))) {
                $errors[] = 'Auteur incorrect';
            }

            var_dump($errors);
            // if validation is ok, insert and redirection
            if (empty($errors)) {
                $articleManager = new ArticleManager();
                $id = $articleManager->insertArticle($item);

                return null;
            }
        }

        return $this->twig->render('Admin/add-articles.html.twig', ['session' => $_SESSION, 'user' => $user_profile]);
    }

    /**
     * Show informations for a specific article.
     */
    public function showArticle(int $id): string
    {
        $articlesManager = new ArticleManager();
        $article = $articlesManager->selectOneById($id);

        return $this->twig->render('Admin/show-article.html.twig', ['articles' => $article]);
    }

    /**
     * Edit a specific article.
     */
    public function editArticle(int $id): ?string
    {
        $articlesManager = new ArticleManager();
        $article = $articlesManager->selectOneById($id);

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // clean $_POST data
            $article = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $articlesManager->editArticle($article);

            header('Location: /admin/articles/show?id='.$id);

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('Admin/edit.html.twig', ['article' => $article]);
    }

    /**
     * Delete a specific article.
     */
    public function deleteArticle(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $id = trim($_POST['id']);
            $articlesManager = new ArticleManager();
            $articlesManager->delete((int) $id);

            header('Location:/admin/articles');
        }
    }

    // Edit a specific item
}