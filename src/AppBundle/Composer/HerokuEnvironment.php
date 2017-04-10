<?php

namespace AppBundle\Composer;

class HerokuEnvironment
{
    /**
     * Populate Heroku environment
     */
    public static function populateEnvironment()
    {
        $db = getenv('CLEARDB_DATABASE_URL');
        $memcache = getenv('MEMCACHEDCLOUD_SERVERS');
        $elasticSearch = getenv('BONSAI_URL');

        /**
         * Database
         */
        if ($db) {
            $url = parse_url($db);

            putenv("DATABASE_HOST=" . $url['host']);
            putenv("DATABASE_USER=" . $url['user']);
            putenv("DATABASE_PASSWORD=" . $url['pass']);
            putenv("DATABASE_NAME=" . substr($url['path'], 1));
        }

        /**
         * Memcache
         */
        if ($memcache) {
            $url = parse_url($memcache);

            putenv("MEMCACHE_SERVER=" . $url['host']);
            putenv("MEMCACHE_PORT=" . $url['port']);
            putenv("MEMCACHE_USERNAME=" . getenv('MEMCACHEDCLOUD_USERNAME'));
            putenv("MEMCACHE_PASSWORD=" . getenv('MEMCACHEDCLOUD_PASSWORD'));
            putenv("MEMCACHE_SESSION_PREFIX=TASKIO");
        }

        /**
         * ElasticSearch
         */
        if ($elasticSearch) {
            $url = parse_url($elasticSearch);

            putenv("ELASTIC_HOST=" . $url['host']);
            putenv("ELASTIC_SCHEME=" . $url['scheme']);
            putenv("ELASTIC_USER=" . $url['user']);
            putenv("ELASTIC_PASSWORD=" . $url['pass']);
            putenv("ELASTIC_PORT=" . ($url['scheme'] === 'https' && !isset($url['port'])) ? 443 : $url['port']);
        }
    }
}
