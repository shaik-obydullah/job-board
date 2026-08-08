<?php

namespace App\Support;

class CacheKeys
{
    public const POPULAR_CATEGORIES = 'job_categories.popular';

    public const ALL_CATEGORIES = 'job_categories.all';

    public const HOME_STATS = 'home.stats';

    public const HOME_COMPANIES = 'home.companies';

    public static function categoryKeys(): array
    {
        return [self::POPULAR_CATEGORIES, self::ALL_CATEGORIES];
    }
}
