<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    /**
     * List items.
     */
    public function index(): string
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('name');

        return $this->twig->render('Admin/all_users.html.twig', ['users' => $users]);
    }

        public function delete(): void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $id = trim($_POST['id']);
                $userManager = new UserManager();
                $userManager->delete((int) $id);

                header('Location:/admin/all_users');
            }
        }
}
