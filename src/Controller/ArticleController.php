<?php

namespace App\Controller;

use App\Model\ArticleManager;

class ArticleController extends AbstractController
{
    /**
     * List items.
     */
    public function index(): string
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll('title');

        return $this->twig->render('Article/index.html.twig', ['articles' => $articles]);
    }

    /**
     * Show informations for a specific item.
     */
    public function show(int $id): string
    {
        $itemManager = new ArticleManager();
        $item = $itemManager->selectOneById($id);

        return $this->twig->render('Article/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific item.
     */
    public function edit(int $id): ?string
    {
        $itemManager = new ArticleManager();
        $item = $itemManager->selectOneById($id);

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $itemManager->update($item);

            header('Location: /articles/show?id='.$id);

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('Article/edit.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * Add a new item.
     */
    public function add(): ?string
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)
            // add image

            $uploadDir = 'public/uploads/';

            $uploadFile = $uploadDir.basename($_FILES['picture']['name']);

            $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);

            $authorizedExtensions = ['jpg', 'jpeg', 'png'];

            $maxFileSize = 2000000;

            // if validation is ok, insert and redirection
            $itemManager = new ArticleManager();
            $id = $itemManager->insert($item);

            header('Location:/articles/show?id='.$id);

            return null;
        }

        return $this->twig->render('Article/add.html.twig');
    }

    /**
     * Delete a specific item.
     */
    public function delete(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $id = trim($_POST['id']);
            $itemManager = new ArticleManager();
            $itemManager->delete((int) $id);

            header('Location:/articles');
        }
    }
}
