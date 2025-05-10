<?php


namespace App\Http\Classes;


class Pages
{
    public const PUBLIC_PERMISSION = 'public';
    public const ADMIN_PERMISSION = 'admin';

    public static $pages = [
        'index' => [
            'path' => 'index',
            'title' => 'Главная',
            'permission' => self::PUBLIC_PERMISSION,
            'dropdown' => []
        ],
        'library' => [
            'path' => 'library',
            'title' => 'Библиотека',
            'permission' => self::PUBLIC_PERMISSION,
            'dropdown' => [
                'anime' => [
                    'path' => 'library.anime',
                    'title' => 'Аниме',
                    'permission' => self::PUBLIC_PERMISSION,
                ],
                /*'manga' => [
                    'path' => 'library.anime',
                    'title' => 'Манга',
                    'permission' => self::PUBLIC_PERMISSION,
                ],*/
            ]
        ],
        'about' => [
            'path' => 'about',
            'title' => 'О сайте',
            'permission' => self::PUBLIC_PERMISSION,
            'dropdown' => []
        ],
        /*'videos' => [
            'path' => 'video',
            'title' => 'Видео',
            'permission' => self::PUBLIC_PERMISSION
        ],
        'forum' => [
            'path' => 'forum',
            'title' => 'Форум',
            'permission' => self::PUBLIC_PERMISSION
        ],
        'admin' => [
            'path' => 'admin',
            'title' => 'Админка',
            'permission' => self::ADMIN_PERMISSION
        ],*/
        'admin' => [
            'path' => 'admin',
            'title' => 'Админка',
            'permission' => self::ADMIN_PERMISSION,
            'dropdown' => []
        ],
    ];
}
