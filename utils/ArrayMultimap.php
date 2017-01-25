<?php
/**
 * Copyright (C) 2011 Mindplex Media, LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
 * file except in compliance with the License. You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

namespace ecs\utils;

/**
 * Multimap is similar to a Map but allows mapping multiple values with a single key.
 *
 * @package mindplex-commons-collections
 * @author Abel Perez
 * @author chris
 */
class ArrayMultimap implements Multimap
{
    /**
     * @var array
     */
    private $map;

    /**
     * @var int
     */
    private $size = 0;

    public function __construct() {
        $this->map = [];
    }

    /**
     * Removes all the key value pairs from the multimap.
     */
    public function clear() {
        unset($this->map);
        $this->map = [];
        $this->size = 0;
    }

    /**
     * Checks if the multimap contains the specified key value pair.
     *
     * @param string $key Key to search
     * @param mixed $value Value to search
     * @return bool
     */
    public function containsEntry($key, $value) {
        if (!array_key_exists($key, $this->map)) {
            return false;
        }

        foreach ($this->map[$key] as $k => $v) {
            if ($value == $v) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the multimap contains the specified key.
     *
     * @param string $key Key to search for
     * @return bool
     */
    public function containsKey($key) {
        return array_key_exists($key, $this->map);
    }

    /**
     * Checks if the multimap contains the specified value.
     *
     * @param mixed $value Value to search for
     * @return bool
     */
    public function containsValue($value) {
        foreach ($this->map as $k => $v) {
            if (in_array($value, $v)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets all the key value pairs in the multimap.
     * @return Pair[]
     */
    public function entries() {
        $entries = [];
        foreach ($this->map as $k => $v) {
            foreach ($v as $value) {
                $entries[] = new Pair($k, $value);
            }
        }

        return $entries;
    }

    /**
     * Gets all the values that map to the specified key.
     *
     * @param string $key Key to search for
     * @return array
     */
    public function get($key) {
        if (!array_key_exists($key, $this->map)) {
            return [];
        }

        return $this->map[$key];
    }

    /**
     * Checks if the multimap contains key value pairs.
     *
     * @return bool
     */
    public function isEmpty() {
        return ($this->size <= 0);
    }

    /**
     * Get's all the keys in the multimap.
     *
     * @return array
     */
    public function keys() {
        $keys = [];
        foreach ($this->map as $k => $v) {
            $keys[] = $k;
        }

        return $keys;
    }

    /**
     * Get's all the unique keys in the multimap.
     *
     * @return array
     */
    public function keySet() {
        return array_unique($this->keys());
    }

    /**
     * Put's the specified key value pair in the multimap.
     *
     * @param string $key Key to put
     * @param mixed $value Value to put
     */
    public function put($key, $value) {
        if (!array_key_exists($key, $this->map)) {
            $this->map[$key] = [$value];
        } else {
            $this->map[$key][] = $value;
        }

        $this->size++;
    }

    /**
     * Put's multiple values in the multimap.
     *
     * @param string $key Key to put
     * @param array $values Values to put
     */
    public function putAll($key, array $values) {
        foreach ($values as $v) {
            $this->put($key, $v);
        }
    }

    /**
     * Removes the specified key value pair in the multimap.
     *
     * @param string $key Key to remove
     * @param mixed $value Value to remove
     * @return bool Whether the specified key value pair was removed from the map
     * @throws \RuntimeException When the given key could not be found
     */
    public function remove($key, $value) {
        if (!array_key_exists($key, $this->map)) {
            throw new \RuntimeException(sprintf('Key "%s" not found.', $key));
        }

        $result = false;
        $values = $this->map[$key];
        foreach ($values as $k => $v) {
            if ($value == $v) {
                unset($values[$k]);
                $result = true;
                $this->size--;
            }
        }

        $this->map[$key] = $values;
        return $result;
    }

    /**
     * Removes all values for the given key.
     *
     * @param string $key Key of values to remove
     * @return int The number of values removed
     * @throws \RuntimeException When the given key could not be found
     */
    public function removeAll($key) {
        if (!array_key_exists($key, $this->map)) {
            throw new \RuntimeException(sprintf('Key "%s" not found.', $key));
        }

        $values = $this->map[$key];
        $count = count($values);
        unset($this->map[$key]);
        $this->size -= $count;

        return $count;
    }

    /**
     * Replaces the values for the given key.
     *
     * @param string $key Key to replace
     * @param array $values Values to replace
     * @throws \RuntimeException When the given key could not be found
     */
    public function replaceValues($key, array $values) {
        if (!array_key_exists($key, $this->map)) {
            throw new \RuntimeException(sprintf('Key "%s" not found.', $key));
        }

        $this->map[$key] = $values;
    }

    /**
     * Get's the number of key value pairs.
     *
     * @return int
     */
    public function size() {
        return $this->size;
    }

    /**
     * Get's all the values.
     *
     * @return array
     */
    public function values() {
        $values = [];
        foreach ($this->map as $k => $v) {
            foreach ($v as $value) {
                $values[] = $value;
            }
        }

        return $values;
    }
}
