<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index'],
    // admin controller
    'admin/all_users' => ['UserController', 'all_users'],
    'admin/delete_user' => ['UserController', 'delete', ['id']],

    // user controller
    'register_successfull' => ['UserController', 'add'],
    'login_member' => ['UserController', 'isLogin'],
    'member/user_profile' => ['UserController', 'show_profile_user', ['id']],
    'member/edit_profile' => ['UserController', 'edit', ['id']],
    'member/is_logout' => ['UserController', 'isLogout'],
    'member/edit_avatar' => ['UserController', 'edit_avatar', ['id']],

    // article controller
    'admin/articles' => ['AdminController', 'seeArticles'],
    'articles' => ['ArticlesController', 'articlesMember'],
    'articles/show' => ['ArticlesController', 'showArticleMember', ['id']],

    'admin/addArticles' => ['ArticlesController', 'addArticle'],
    'admin/articles/show' => ['ArticlesController', 'showArticle', ['id']],
    'admin/articles/delete' => ['ArticlesController', 'deleteArticle'],
    'admin/articles/edit' => ['ArticlesController', 'editArticle', ['id']],

    // manga and anime controller
    'manga' => ['MangaController', 'listManga'],

    'anime' => ['AnimeController', 'listAnime'],
    'anime/search' => ['AnimeController', 'searchAnime', ['query']],

    'anime/show' => ['AnimeController', 'showAnimeMoreInfo', ['id']],
    'manga/show' => ['MangaController', 'showMangaMoreInfo', ['id']],

    // all likes
    'anime/like' => ['HomeController', 'likeAnime', ['id']],
    'manga/like' => ['HomeController', 'likeManga', ['id']],
];
