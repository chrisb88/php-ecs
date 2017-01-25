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
interface Multimap
{
    /**
     * Removes all the key value pairs from the multimap.
     */
    public function clear();

    /**
     * Checks if the multimap contains the specified key value pair.
     *
     * @param string $key Key to search
     * @param mixed $value Value to search
     * @return bool
     */
    public function containsEntry($key, $value);

    /**
     * Checks if the multimap contains the specified key.
     *
     * @param string $key Key to search for
     * @return bool
     */
    public function containsKey($key);

    /**
     * Checks if the multimap contains the specified value.
     *
     * @param mixed $value Value to search for
     * @return bool
     */
    public function containsValue($value);

    /**
     * Gets all the key value pairs in the multimap.
     * @return Pair[]
     */
    public function entries();

    /**
     * Gets all the values that map to the specified key.
     *
     * @param string $key Key to search for
     * @return array
     */
    public function get($key);

    /**
     * Checks if the multimap contains key value pairs.
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Get's all the keys in the multimap.
     *
     * @return array
     */
    public function keys();

    /**
     * Get's all the unique keys in the multimap.
     *
     * @return array
     */
    public function keySet();

    /**
     * Put's the specified key value pair in the multimap.
     *
     * @param string $key Key to put
     * @param mixed $value Value to put
     */
    public function put($key, $value);

    /**
     * Put's multiple values in the multimap.
     *
     * @param string $key Key to put
     * @param array $values Values to put
     */
    public function putAll($key, array $values);

    /**
     * Removes the specified key value pair in the multimap.
     *
     * @param string $key Key to remove
     * @param mixed $value Value to remove
     * @return bool Whether the specified key value pair was removed from the map
     * @throws \RuntimeException When the given key could not be found
     */
    public function remove($key, $value);

    /**
     * Removes all values for the given key.
     *
     * @param string $key Key of values to remove
     * @return int The number of values removed
     * @throws \RuntimeException When the given key could not be found
     */
    public function removeAll($key);

    /**
     * Replaces the values for the given key.
     *
     * @param string $key Key to replace
     * @param array $values Values to replace
     * @throws \RuntimeException When the given key could not be found
     */
    public function replaceValues($key, array $values);

    /**
     * Get's the number of key value pairs.
     *
     * @return int
     */
    public function size();

    /**
     * Get's all the values.
     *
     * @return array
     */
    public function values();
}

/**
 * Pair is a simple container class that holds a key value pair.
 */
class Pair
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Constructs this entry with the specified key value pair.
     *
     * @param string $key Pair key
     * @param mixed $value Pair value
     */
    public function __construct($key, $value) {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Sets the key.
     * @param string $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Sets the value.
     * @param mixed $value
     */
    public function setValue($value) {
        $this->value = $value;
    }
}
