<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Cache;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;

/**
 * Abstract class that inherit doctrine cache provider. Contains basic functionality for caching data.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Cache
 * @link      http://github.com/countlang/countlang
 */
abstract class Cache extends CacheProvider
{
    /** @var int Default time of caching. */
    const DEFAULT_CACHE_TIME = 86400; // 1 day

    /** @var array List of OS that support custom file caching. */
    const FILESYSTEM_CACHE_OS_LIST  = [
        'linux',
        'gnu/kfreebsd',
        'freebsd',
        'netbsd',
        'openbsd',
    ];
    /** @var string Default filesystem cache base directory. */
    const FILESYSTEM_CACHE_BASE_DIR = '/tmp';
    /** @var string Default filesystem cache directory. */
    const FILESYSTEM_CACHE_DIR_NAME = 'countlang';

    /** @var string Cache type APC. */
    const CACHE_TYPE_APC        = 'apc';
    /** @var string Cache type APCu. */
    const CACHE_TYPE_APCU       = 'apcu';
    /** @var string Cache type Filesystem. */
    const CACHE_TYPE_FILESYSTEM = 'filesystem';
    /** @var string Cache type Array. */
    const CACHE_TYPE_ARRAY      = 'array';

    /** @var array Cache for requested cache by type. */
    private static $typeCache = [];

    /** @var CacheProvider Cache for cache that was not specified during request. */
    private static $cache;

    /** @var string|null Requested cache type. */
    private $cacheType;

    /**
     * Cache type that will be used might be specified in constructor.
     *
     * @param string|null $cacheType
     */
    public function __construct($cacheType = null)
    {
        $this->cacheType = $cacheType;
    }

    /**
     * Fetches an entry from the cache.
     *
     * @param string $id
     *
     * @return mixed|false
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    protected function doFetch($id)
    {
        return $this->getCacheProvider()->fetch($id);
    }

    /**
     * Puts data into the cache.
     *
     * @param string $id
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    protected function doContains($id)
    {
        return $this->getCacheProvider()->contains($id);
    }

    /**
     * Puts data into the cache.
     *
     * @param string $id
     * @param string $data
     * @param int    $lifeTime
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    protected function doSave($id, $data, $lifeTime = self::DEFAULT_CACHE_TIME)
    {
        $lifeTime = 0 !== $lifeTime ?: self::DEFAULT_CACHE_TIME;

        return $this->getCacheProvider()->save($id, $data, $lifeTime);
    }

    /**
     * Deletes a cache entry.
     *
     * @param string $id
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    protected function doDelete($id)
    {
        return $this->getCacheProvider()->delete($id);
    }

    /**
     * Flushes all cache entries.
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    protected function doFlush()
    {
        return $this->getCacheProvider()->flushAll();
    }

    /**
     * Retrieves cached information from the data store.
     *
     * @return array|null
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    protected function doGetStats()
    {
        return $this->getCacheProvider()->getStats();
    }

    /**
     * Returns cache provider (DoctrineCache object).
     *
     * @return CacheProvider
     *
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     *
     * @codeCoverageIgnore
     */
    private function getCacheProvider()
    {
        if (null === $this->cacheType) {
            if (null !== self::$cache) {
                return self::$cache;
            }

            $cacheProviders = array_values(array_filter([
                $this->getApcCache(),
                $this->getApcuCache(),
                $this->getFilesystemCache(),
                $this->getArrayCache(),
            ]));

            return self::$cache = new ChainCache($cacheProviders);
        }

        if (array_key_exists($this->cacheType, self::$typeCache)) {
            return self::$typeCache[$this->cacheType];
        }

        if (!in_array($this->cacheType, $this->getAllowedCacheTypesMap(), true)) {
            throw new \UnexpectedValueException("Cache provider {$this->cacheType} is not available.");
        }

        switch ($this->cacheType) {
            case self::CACHE_TYPE_APC:
                $cacheProvider = $this->getApcCache();
                break;
            case self::CACHE_TYPE_APCU:
                $cacheProvider = $this->getApcuCache();
                break;
            case self::CACHE_TYPE_FILESYSTEM:
                $cacheProvider = $this->getFilesystemCache();
                break;
            case self::CACHE_TYPE_ARRAY:
                $cacheProvider = $this->getArrayCache();
                break;
            default:
                $cacheProvider = null;
                break;
        }

        if (null === $cacheProvider) {
            throw new \RuntimeException('Error during cache initialization.');
        }

        return self::$typeCache[$this->cacheType] = $cacheProvider;
    }

    /**
     * Returns doctrine ApcCache cache provider if it is available.
     *
     * @return ApcCache|null
     *
     * @codeCoverageIgnore
     */
    private function getApcCache()
    {
        if (!extension_loaded('apc') || !ini_get('apc.enabled')) {
            return null;
        }

        return new ApcCache();
    }

    /**
     * Returns doctrine ApcuCache cache provider if it is available.
     *
     * @return ApcuCache|null
     *
     * @codeCoverageIgnore
     */
    private function getApcuCache()
    {
        if (!extension_loaded('apcu') || !ini_get('apc.enabled')) {
            return null;
        }

        return new ApcuCache();
    }

    /**
     * Returns doctrine FilesystemCache cache provider if it is available.
     *
     * @return FilesystemCache|null
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     *
     * @codeCoverageIgnore
     */
    private function getFilesystemCache()
    {
        if (!in_array(strtolower(PHP_OS), self::FILESYSTEM_CACHE_OS_LIST, true)) {
            return null;
        }

        $fullPath = self::FILESYSTEM_CACHE_BASE_DIR . DIRECTORY_SEPARATOR .
            trim(self::FILESYSTEM_CACHE_DIR_NAME, DIRECTORY_SEPARATOR);

        if (!file_exists($fullPath) || !is_writable($fullPath)) {
            return null;
        }

        if (!@mkdir($fullPath, 0777, true) && !is_dir($fullPath)) {
            return null;
        }

        return new FilesystemCache($fullPath);
    }

    /**
     * Returns doctrine ArrayCache.
     *
     * @return ArrayCache
     *
     * @codeCoverageIgnore
     */
    private function getArrayCache()
    {
        return new ArrayCache();
    }

    /**
     * Returns array with allowed doctrine cache providers names.
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    private function getAllowedCacheTypesMap()
    {
        return [
            self::CACHE_TYPE_APC,
            self::CACHE_TYPE_APCU,
            self::CACHE_TYPE_FILESYSTEM,
            self::CACHE_TYPE_ARRAY,
        ];
    }
}