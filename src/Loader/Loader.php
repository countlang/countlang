<?php
/**
 * This file is part of countlang package.
 *
 * Copyright 2017 Andrii Bibena <countlang@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CountLang\Loader;

use CountLang\Collection\Collection;

/**
 * Loads Collection object from source file.
 *
 * Parses and validates source file and generates Collection object.
 *
 * @author    Andrii Bibena <countlang@gmail.com>
 * @copyright 2017 Andrii Bibena <countlang@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @package   countlang/Loader
 * @link      http://github.com/countlang/countlang
 */
abstract class Loader
{
    /** @var string Directory path to sources files. */
    const SOURCES_DIR_NAME = 'resources';

    /** @var Collection|null Collection object. */
    private $collection;

    /**
     * Gets Collection object loaded from cache or source file.
     *
     * @return Collection
     *
     * @throws \DomainException
     * @throws \RuntimeException
     */
    public function getCollection()
    {
        if (null !== $this->collection) {
            return $this->collection;
        }

        return $this->collection = $this->generateCollection();
    }

    /**
     * Generates Collection object from source file.
     *
     * @return Collection
     */
    abstract protected function generateCollection();

    /**
     * Returns array with decoded source file data.
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     */
    protected function getSourceFileData()
    {
        $sourceFileName = trim($this->getSourceFileName(), DIRECTORY_SEPARATOR);
        $sourceFilePath = $this->getSourceFilePath();

        if (!is_file($sourceFilePath)) {
            // @codeCoverageIgnoreStart
            throw new \UnexpectedValueException("File with name {$sourceFileName} not found in dir {$this->getSourceDirPath()}.");
            // @codeCoverageIgnoreEnd
        }
        $rawData = file_get_contents($sourceFilePath);

        if ('' === $rawData) {
            // @codeCoverageIgnoreStart
            throw new \UnexpectedValueException("Source file {$sourceFilePath} is empty.");
            // @codeCoverageIgnoreEnd
        }
        $parsedData = @json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // @codeCoverageIgnoreStart
            throw new \UnexpectedValueException("Error happened during parsing json from file {$sourceFilePath} with message " . json_last_error_msg());
            // @codeCoverageIgnoreEnd
        }

        return $parsedData;
    }

    /**
     * Returns source file name.
     *
     * @return string
     */
    abstract protected function getSourceFileName();

    /**
     * Returns relative path to source dir.
     *
     * @return string
     */
    private function getSourceDirPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR .
               '..' . DIRECTORY_SEPARATOR . '..' .
               DIRECTORY_SEPARATOR . self::SOURCES_DIR_NAME;
    }

    /**
     * Returns full path to source file.
     *
     * @return string
     */
    private function getSourceFilePath()
    {
        return $this->getSourceDirPath() . DIRECTORY_SEPARATOR .
               trim($this->getSourceFileName(), DIRECTORY_SEPARATOR);
    }

    /**
     * Returns array with prepared source file data.
     *
     * @param array  $input
     * @param array  $output
     * @param string $keyPrefix
     *
     * @return array
     */
    protected function prepareSourceFileData(array $input, array $output = [], $keyPrefix = '')
    {
        foreach ($input as $key => $item) {
            if (is_int($key)) {
                $output[$keyPrefix][$key] = $item;
            } else {
                $newKeyPrefix = '' === $keyPrefix ? $key : "{$keyPrefix}_{$key}";

                if (is_array($item) && 0 !== count($item)) {
                    $output = array_merge($output, $this->prepareSourceFileData($item, $output, $newKeyPrefix));
                } else {
                    $output[$newKeyPrefix] = $item;
                }
            }
        }

        return $output;
    }
}