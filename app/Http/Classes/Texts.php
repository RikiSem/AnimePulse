<?php


namespace App\Http\Classes;


class Texts
{
    public const EVENT_TEXTS = [
        'add_to_favorite' => 'Добавлено в избранное',
        'remove_from_favorite' => 'Убрано из избранного',
        'change_status' => 'Статус изменен',
        'remove_status' => 'Статус убран',
        'comment_send' => 'Комментарий отправлен',
        'review_created' => 'Рецензия создана',
        'review_updated' => 'Рецензия обновлена'

    ];

    public const FILTER_STUDIO = 'Выберите студию';
    public const FILTER_RELEASE_YEAR = 'Выберите год выхода';
    public const FILTER_USER_STATUS = 'Выберите статус';
    public const FILTER_ANIME_STATUS = self::FILTER_USER_STATUS;
    public const FILTER_ANIME_TYPE = 'Выберите тип';

    public static function getAboutText(string $host, int $animeCount = 0, int $reviewCount = 0)
    {
        $text = [
            sprintf("Привет, путник мира аниме! Добро пожаловать на %s — твою личную библиотеку тайтлов и рецензий. Здесь ты найдешь всё: от эпичных саг вроде 'Евангелиона' до уютных повседневок, о которых даже не подозревал.", $host),
            sprintf("Мы — команда фанатов, которые в октябре 2024 года решили, что пора создать место, где каждый сможет не только найти новое аниме, но и поделиться своими мыслями. У нас уже %s тайтлов и %s рецензий от таких же отаку, как ты. Ищешь меха? Романтику? Жуткие триллеры? Фильтры помогут, а комментарии и рецензии подскажут, стоит ли тратить выходные на очередной исекай.", $animeCount, $reviewCount),
            "Чем мы гордимся? Все рецензии — живые, написаны от души, а не скопированы с Википедии. Это не просто база данных, а уголок, где анимешники собираются, чтобы обсудить, поспорить и вдохновиться.",
            "Хочешь стать частью этого? Регистрируйся, пиши свои рецензии или просто ныряй в каталог — возможно, твой следующий любимый тайтл уже ждет. Есть идеи или вопросы? Cтучись в к нам в телегу.",
        ];

        return $text;
    }

    public static function getRules()
    {
        return \Illuminate\Support\Facades\Storage::get(basename('/../../storage/rules.txt'));
        //return Storage::get('/../../public/storage/rules.txt');
    }
}
