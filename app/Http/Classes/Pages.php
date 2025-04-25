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
            'permission' => self::PUBLIC_PERMISSION
        ],
        'library' => [
            'path' => 'library',
            'title' => 'Библиотека',
            'permission' => self::PUBLIC_PERMISSION
        ],
        'about' => [
            'path' => 'about',
            'title' => 'О сайте',
            'permission' => self::PUBLIC_PERMISSION
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
        ],*/
        'admin' => [
            'path' => 'admin',
            'title' => 'Админка',
            'permission' => self::ADMIN_PERMISSION
        ],
    ];
}
