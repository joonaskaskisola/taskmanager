<?php

namespace AppBundle\Composer;

class HerokuEnvironment
{
    /**
     * Populate Heroku environment
     */
    public static function populateEnvironment()
    {
        /**
         * Database
         */
        $url = getenv('CLEARDB_DATABASE_URL');

        if ($url) {
            $url = parse_url($url);
            putenv("DATABASE_HOST={$url['host']}");
            putenv("DATABASE_USER={$url['user']}");
            putenv("DATABASE_PASSWORD={$url['pass']}");

            $db = substr($url['path'], 1);
            putenv("DATABASE_NAME={$db}");
        }

        /**
         * Memcache
         */
        $memcache = parse_url(getenv('MEMCACHEDCLOUD_SERVERS'));
        putenv("MEMCACHE_SERVER={$memcache['host']}");
        putenv("MEMCACHE_PORT={$memcache['port']}");
        putenv("MEMCACHE_SESSION_PREFIX=TASKIO");
        putenv('MEMCACHE_USERNAME=' . getenv('MEMCACHEDCLOUD_USERNAME'));
        putenv('MEMCACHE_PASSWORD=' . getenv('MEMCACHEDCLOUD_PASSWORD'));
    }
}
