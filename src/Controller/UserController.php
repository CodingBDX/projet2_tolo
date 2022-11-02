<?php

namespace App\Controller;

use App\Model\Session;
use App\Model\UserManager;

class UserController extends AbstractController
{
    /**
     * List items.
     */
    public function all_users(): string
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('name');

        return $this->twig->render('Admin/all_users.html.twig', ['users' => $users]);
    }

        public function delete(): void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $id = trim($_POST['user_id']);
                $userManager = new UserManager();
                $userManager->selectOneById($id);

                $userManager->delete((int) $id);

                header('Location:/admin/all_users');
            }
        }

        public function add(): ?string
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                // clean $_POST data
                $user = array_map('trim', $_POST);

                // TODO validations (length, format...)

                // if validation is ok, insert and redirection
                $userManager = new UserManager();
                $register = $userManager->insert($user);
                header('Location:/member/user_profile?id='.$register);

                return null;
            }

            return $this->twig->render('_Modal/register.html.twig');
        }

         public function show_profile_user(string $id): string
         {
             $userManager = new UserManager();
             $user_profile = $userManager->selectOneById($id);
             if ('GET' === $_SERVER['REQUEST_METHOD']) {
                 //  init session
                 $session = new Session();

                 $session->write('id', $_GET['id']);
                 setcookie('user_id', $_GET['id'], time() + 3600, '/');

                 setcookie('user_mail', $user_profile['mail'], time() + 3600, '/');

                 $session->write('mail', $user_profile['mail']);
                 //  $session->write('password', $_GET['password']);
                 // end init session

                 return $this->twig->render(
                     'Member/user_profile.html.twig',
                     [
                         'user' => $user_profile,
                         'session' => $session,
                     ]
                 );
             }

             header('Location: /member/user_profile?id='.$id);

             return 'error';
         }

    /**
     * Edit a specific item.
     */
    public function edit(int $id): ?string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // clean $_POST data
            $user = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $userManager->update($user);

            header('Location: /member/user_profile?id='.$id);

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('member/user_profile.html.twig', [
            'user' => $user,
        ]);
    }
}
