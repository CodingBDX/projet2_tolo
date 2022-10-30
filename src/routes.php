<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    'home' => ['HomeController', 'index'],
    // admin controller
    'admin/all_users' => ['UserController', 'all_users'],
    'admin/delete_user' => ['UserController', 'delete'],

    // user controller
    'register_successfull' => ['UserController', 'add'],
    'member/user_profile' => ['UserController', 'show_profile_user', ['id']],
    'member/edit_profile' => ['UserController', 'edit', ['id']],
    'member/login' => ['UserController', 'login_user', ['id']],

    // article controller
    'articles' => ['ArticleController', 'index'],
    'articles/edit' => ['ArticleController', 'edit', ['id']],
    'articles/show' => ['ArticleController', 'show', ['id']],
    'articles/add' => ['ArticleController', 'add'],
    'articles/delete' => ['ArticleController', 'delete'],

    // manga and anime controller
    'manga' => ['MangaController', 'listManga'],

    'anime' => ['AnimeController', 'listAnime'],

    'anime/show' => ['AnimeController', 'showAnimeMoreInfo', ['id']],
    'manga/show' => ['MangaController', 'showMangaMoreInfo', ['id']],

    'top' => ['TopController', 'getTop'],
];
