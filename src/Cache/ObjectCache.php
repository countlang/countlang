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

/**
 * Contains basic functionality for caching objects.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Cache
 * @link      http://github.com/countlang/countlang
 */
class ObjectCache extends Cache
{
    /** @var bool Whether object cache enabled */
    static private $enabled = true;

    /**
     * Enables object cache.
     */
    public function enableCache()
    {
        self::$enabled = true;
    }

    /**
     * Disables object cache.
     */
    public function disableCache()
    {
        self::$enabled = false;
    }

    /**
     * Returns whether cache contains requested object.
     *
     * @param string|object $className
     * @param array|null    $initParams
     *
     * @return bool
     */
    public function containsObject($className, $initParams = null)
    {
        $id = $this->getObjectId($className, $initParams);

        return $this->contains($id);
    }

    /**
     * Returns requested object from cache or initialize one .
     *
     * @param string|object $className
     * @param callable|null $init
     * @param array|null    $initParams
     *
     * @return object
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function fetchObject($className, $init = null, $initParams = null)
    {
        $id = $this->getObjectId($className, $initParams);

        if (self::$enabled && $this->contains($id)) {
            return $this->fetch($id);
        }

        $object = null === $init ?
            new $className($initParams) :
            call_user_func_array($init, $initParams);

        self::$enabled && $this->save($id, $object);

        return $object;
    }

    /**
     * Saves requested object to cache.
     *
     * @param object $object
     *
     * @return bool
     */
    public function saveObject($object)
    {
        $id = $this->getObjectId($object);

        return $this->save($id, $object);
    }

    /**
     * Deletes requested object from cache.
     *
     * @param string|object $className
     * @param array|null    $initParams
     *
     * @return bool
     */
    public function deleteObject($className, $initParams = null)
    {
        $id = $this->getObjectId($className, $initParams);

        return $this->delete($id);
    }

    /**
     * Generates unique id for object by class name and init params.
     *
     * @param string|object $className
     * @param array|null    $initParams
     *
     * @return string
     */
    private function getObjectId($className, $initParams = null)
    {
        if (is_object($className)) {
            $className = get_class($className);
        }

        return md5($className . json_encode($initParams));
    }
}