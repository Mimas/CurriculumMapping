<?php
/**
 * Jorum API Formatters
 *
 * Html
 *
 * Helper class to output Jorum stuff into pretty HTML
 * The only purpose of this is to assist in the understanding the API
 * by providing a human readable output
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service\Jorum\Formatters;

/**
 * Class Html
 * @package MIMAS\Service\Jorum\Formatters
 */
class Html
{

    /**
     * Get something like /rest/items/9731 or /bitstreams/150886/retrieve
     * @param $partial
     * @return string
     */
    static function makeLink($partial)
    {
        $url = '';
        if (strpos($partial, '/rest') === false) {
            $partial = '/rest' . $partial;
        }
        $bits = explode('/', $partial);

        if (count($bits) >= 3) {
            $url = $bits[2] . '.html/' . $bits[3];
        }

        if (isset($bits[4]) && $bits[4] == 'retrieve') {
            $url .= '/retrieve';
        }

        return link_to($url, $partial);
    }

    /**
     * Make the expandable attributes clickable (URLs to the expanded version)
     * @param $partial
     * @param $label
     * @return string
     */
    static function makeExpands($partial, $label)
    {
        // return if not string
        if (!is_string($label)) {
            return $label;
        }

        $url = '';
        if (strpos($partial, '/rest') === false) {
            $partial = '/rest' . $partial;
        }
        $bits = explode('/', $partial);

        if (count($bits) >= 3) {
            $url = $bits[2] . '.html/' . $bits[3];
        }

        if (isset($bits[4]) && $bits[4] == 'retrieve') {
            $url .= '/retrieve';
        }

        return link_to($url . '?expand=' . $label, $label);

    }

    /**
     * Get the HTML output for key/value. Optionally make a link out of it
     * @param $key
     * @param $value
     * @param string $link
     */
    static function get($key, $value, $link = '')
    {

        // $value = $iterator->current();
        $key = str_replace('*', '', $key);


        echo "<span style=\"font-family: \"courier new\", courier;\">";

        echo "<strong>$key</strong>";
        echo ' ';

        echo "<span style=\"color: rgb(33, 84, 115);\" class=\"red\">";

        switch ($value) {
            case is_bool($value):
                echo($value ? 'true' : 'false');
                break;
            case is_long($value):
            case is_float($value):
            case is_double($value):
            case is_int($value):
                echo $value;
                break;
            case is_string($value);
                if (stripos($key, 'link') !== false) {
                    echo self::makeLink($value);
                } else {
                    echo "$value ";
                }
                break;
            case is_array($value):
                if (count($value) > 0) {
                    if (isset($v[0])) {
                        $y = $value[0];

                        if (is_object($y)) {
                            echo "</span>\n";
                            foreach ($value as $j => $object) {
                                $object->toHtml();
                            }
                            echo "<span style=\"color: rgb(204, 0, 0);\" class=\"red\">";

                        } else {
                            $i = 0;
                            foreach ($value as $string) {
                                if ($i > 0) {
                                    echo ", ";
                                }
                                if (strpos($string, 'http') !== false) {
                                    echo "<a href=\"$string\">$string</a>";
                                } else {
                                    if (stripos($key, 'expand')) {
                                        echo "<a href=\"#\">$string</a>";
                                    } else {
                                        echo "$string";
                                    }
                                }
                                $i++;
                            }

                        }
                    } else {
                        echo "<div style=\"padding-left: 16px;\">";
                        foreach ($value as $k => $v) {
                            echo "<span style=\"color: #000000\">$k</span>";
                            if (strpos($key, 'expand') !== false) {
                                echo " => " . self::makeExpands($link, $v) . "<br/>";
                            } else {
                                if (!is_object($v)) {
                                    echo " => $v<br/>";
                                } else {
                                    if (method_exists($v, 'toHtml')) {
                                        $v->toHtml();
                                    } else {
                                        echo " =>" . get_class($v);
                                    }
                                }
                            }
                        }
                        echo "</div>\n";
                    }
                }
                break;

            case is_object($value);

                echo "</span>";
                echo "<div style=\"padding-left: 10px;\">";

                if (method_exists($value, 'toHtml'))
                    $value->toHtml();

                echo "</div>";
                echo "<span style=\"color: rgb(204, 0, 0);\" class=\"red\">";

                break;
        }
        echo "</span>";
        echo "<br/>";

    }
} 