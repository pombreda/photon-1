<?php

/**
 * Utility functions for array manipulation
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Support;

class ArrayLib
{

    /**
     * Similar to array_walk_recursive, but tracks the path of each element
     * 
     * @param array $array Input array
     * @param callable $callback Function to excecute on each element of array, gets arguments ($key, $value, $path)
     * @param array $path Array of tracked indexes
     * @throws \InvalidArgumentException
     * @author Ivan Batić
     * @todo Write tests
     */
    public static function trackRecursive(array $array, $callback, array $path = array())
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Function not callable.');
        }
        foreach ($array as $key => $value) {
            $newPath = array_merge($path, array($key));

            call_user_func_array($callback, array($key, $value, $newPath));
            if (is_array($value)) {
                self::trackRecursive($value, $callback, $newPath);
            }
        }
    }

    /**
     * Extracts a column from a multidimensional array, optionally indexed by $index
     * Recreation of array_column function from php 5.5
     * 
     * @param array $array Input array
     * @param string $column Column to extract
     * @param string $index Optional name of the column to index by
     * @return array Extracted columns
     * @author Ivan Batić
     * @todo Write tests
     */
    public static function column(array $array, $column, $index = null)
    {
        $values = array();
        $keys = array();
        foreach ($array as $key => $entry) {
            if (!is_array($entry)) continue;
            $keys[] = $entry[$column] ? : null;
            $values[] = $entry[$index] ? : null;
        }
        return $index ? array_combine($keys, $values) : $values;
    }

    /**
     * Repack a multidimensional array by specified column as a key
     * @param array $array Input array
     * @param string $column Column to index by
     * @return array Repacked array
     * @author Ivan Batić
     * @todo Write tests
     */
    public static function index(array $array, $column = 'id')
    {
        $output = array();
        foreach ($array as $key => $value) {
            $output[$value[$column]] = $value;
        }
        return $output;
    }

    /**
     * Get the value of a multidimensional array at index specified by path
     * @param array $array Input array
     * @param array $path Ordered array of keys
     * @return mixed Value at the specified path
     * @author Ivan Batić
     * @todo Write tests
     */
    public static function path(array $array, array $path = array())
    {
        $result = $array;
        foreach ($path as $level) $result = isset($result[$level]) ? $result[$level] : null;
        return $result;
    }

    /**
     * Checks to see if multidimensional array is empty
     * @param array $array Input array
     * @return boolean
     */
    public static function isDeepEmpty(array $array)
    {
        if (empty($array)) return true;
        $empty = true;
        array_walk_recursive($array, function($value) use (&$empty) {
            if (!is_array($value)) {
                $empty = false;
            }
        });
        return $empty;
    }

    public static function findByKeyValue(array $array, $key, $value)
    {
        foreach ($array as $element) {
            foreach ($element as $k => $v) {
                if ($k == $key && $v == $value) {
                    return $element;
                }
            }
        }
    }

    public static function getKey(array $array = array(), $key)
    {
        return $array[$key];
    }

}
