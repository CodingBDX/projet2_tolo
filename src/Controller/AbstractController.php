<?php

namespace App\Controller;

use App\Model\Breadcrumb;
use App\Model\Session;
use App\Model\UserManager;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Initialized some Controller common features (Twig...).
 */
abstract class AbstractController
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new DebugExtension());

        // new session for all page

        $session = new Session();
        $this->twig->addGlobal('session', $_SESSION);

        $userManager = new UserManager();

        $this->user = isset($_SESSION['user_id']) ? $userManager->selectOneById((int) $_SESSION['user_id']) : false;
        $this->twig->addGlobal('user', $this->user);

        $userManager->isLogin = isset($_SESSION['user_id']);

        $this->twig->addGlobal('user_id', $userManager->isLogin);

        // active link for menu sidebar

        $active = $_SERVER['PHP_SELF'];

        $this->twig->addGlobal('active', $active);

        //  breadcrumb for all page

        $breadcrumb = new Breadcrumb();
        $breadcrumbMake = $breadcrumb->makeBreadCrumbs();

        $this->twig->addGlobal('breadcrumb', $breadcrumbMake);

        // charging api
    }
}
