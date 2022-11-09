<?php

namespace App\Controller;

use App\Model\Bcrypt;
use App\Model\Session;
use App\Model\UserManager;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Anime\AnimeRequest;
use Jikan\Request\Manga\MangaRequest;

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
                $id = trim($_POST['user.id']);
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

                $bcrypt = new Bcrypt(15);

                $bcrypt->hash($user['password']);

                // if validation is ok, insert and redirection
                $userManager = new UserManager();
                $register = $userManager->insert($user);

                $_SESSION['user_id'] = $user['id'];

                $_SESSION['user_password'] = $user['password'];
                $_SESSION['user_mail'] = $user['mail'];

                //  $session->write('mail', $_GET['mail']);

                $_SESSION['status'] = 'welcome to supra manga site powaa';
                header('Location:/member/user_profile?id='.$register);

                return null;
            }

            return $this->twig->render('_Modal/register.html.twig');
        }

        public function isLogout(): void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                // //  init session

                session_unset();
                session_destroy();

                header('Location:/');
            }
        }

        public function isLogin()
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $credentials = array_map('trim', $_POST);

//      @todo make some controls on email and password fields and if errors, send them to the view
                $userManager = new UserManager();
                $user = $userManager->isLogin($credentials['mail'], $credentials['password']);

                if ($credentials['password'] === $user[0]['password']) {
                    $_SESSION['user_id'] = $user[0]['id'];

                    header('Location: /member/user_profile?id='.$user[0]['id']);

                    exit;
                }

                header('Location: /');
            }

            return $this->twig->render('Home/index.html.twig');
        }

         public function show_profile_user(int $id): string
         {
             if ('GET' === $_SERVER['REQUEST_METHOD']) {
                 //  init session

                 $_SESSION['user_id'] = $id;

                 $api = new MalClient();
                 if (isset($_SESSION['flash'])) {
                     $session->hasFlashes($_SESSION['flash']);
                 } else {
                     $session = '';
                 }
                 if (isset($_COOKIE['anime_like'])) {
                     $animeCookie = $_COOKIE['anime_like'];
                     $requestAnime = $api->getAnime(new AnimeRequest($animeCookie));
                 } else {
                     $requestAnime = '';
                 }
                 if (isset($_COOKIE['manga_like'])) {
                     $mangaCookie = $_COOKIE['manga_like'];
                     $requestManga = $api->getManga(new MangaRequest($mangaCookie));
                 } else {
                     $requestManga = '';
                 }

                 return $this->twig->render(
                     'Member/user_profile.html.twig',
                     [
                         'anime_like' => $requestAnime,
                         'manga_like' => $requestManga,
                     ]
                 );
             }

             header('Location: /member/user_profile?id='.$id);

             return 'error';
         }

/**
 * Edit a specific item.
 */
public function edit_avatar(int $id)
{
    if (isset($_POST['upload'])) {
        $uploadDir = '/public/uploads/';

        // le nom de fichier sur le serveur est celui du nom d'origine du fichier sur le poste du client (mais d'autres stratégies de nommage sont possibles)

        $uploadFile = $uploadDir.basename($_FILES['uploadfile']['name']);

        // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ça y est, le fichier est uploadé

        move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadFile);

        header('Location: /member/user_profile?id='.$id);
    }
}

    public function edit(int $id): ?string
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // clean $_POST data
            $user = array_map('trim', $_POST);

            // TODO validations (length, format...)

            $userManager = new UserManager();

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
