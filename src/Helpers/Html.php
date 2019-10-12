<?php
/**
 * This file is part of the O2System Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */
// ------------------------------------------------------------------------
/**
 * HTML Helper
 *
 * A collection of helper function to work with html.
 */
// ------------------------------------------------------------------------

if ( ! function_exists('meta')) {
    /**
     * meta
     *
     * Generates meta tags from an array of key/values
     *
     * @param    $meta      string|array
     * @param    $content   string|null
     * @param    $type      string
     *
     * @return    string
     */
    function meta($meta = '', $content = '', $type = 'name')
    {
        // Since we allow the data to be passes as a string, a simple array
        // or a multidimensional one, we need to do a little prepping.
        if ( ! is_array($meta)) {
            $meta = [['name' => $meta, 'content' => $content, 'type']];
        } elseif (isset($meta[ 'name' ])) {
            // Turn single array into multidimensional
            $meta = [$meta];
        }

        $output = [];

        foreach ($meta as $attributes) {
            $element = new \O2System\Html\Element('meta');
            $element->attributes->addAttribute('type',
                (isset($attributes[ 'type' ]) && $attributes[ 'type' ] !== 'name') ? 'http-equiv' : 'name');
            $element->attributes->addAttribute('name',
                isset($attributes[ 'content' ]) ? $attributes[ 'content' ] : '');
            $element->attributes->addAttribute('name',
                isset($attributes[ 'content' ]) ? $attributes[ 'content' ] : '');

            if (count($attributes)) {
                foreach ($attributes as $meta => $value) {
                    $element->attributes->addAttribute($meta, $value);
                }
            }

            $output[] = $element;
        }

        return implode(PHP_EOL, $output);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('parse_attributes')) {
    /**
     * parse_attributes
     *
     * Parse attributes from html tag string.
     *
     * @param $string
     *
     * @return array
     */
    function parse_attributes($string)
    {
        $attributes = [];

        if (is_string($string)) {
            if (is_html($string)) {
                $xml = simplexml_load_string(str_replace('>', '/>', $string));
            } else {
                $xml = simplexml_load_string('<tag ' . $string . '/>');
            }

            foreach ($xml->attributes() as $key => $node) {
                $attributes[ $key ] = (string)$node;
            }
        }

        return $attributes;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('remove_tags')) {
    /**
     * remove_tags
     *
     * Remove the tags but keep the content.
     * Note this function always assumed no two tags start the same way (e.g. <tag> and <tags>)
     *
     * @param   string       $html          HTML Source Code
     * @param   string|array $tags          Single HTML Tag | List of HTML Tag
     * @param   bool         $strip_content Whether to display the content of inside tag or erase it
     *
     * @return  string
     */
    function remove_tags($html, $tags, $strip_content = false)
    {
        $content = '';
        if ( ! is_array($tags)) {
            $tags = (strpos($html, '>') !== false ? explode('>', str_replace('<', '', $tags)) : [$tags]);
            if (end($tags) == '') {
                array_pop($tags);
            }
        }
        foreach ($tags as $tag) {
            if ($strip_content) {
                $content = '(.+</' . $tag . '[^>]*>|)';
            }

            $html = preg_replace('#</?' . $tag . '[^>]*>' . $content . '#is', '', $html);
        }

        return $html;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('extract_tag')) {
    /**
     * extract_tag
     *
     * Extract content inside tag.
     *
     * @param  string  $html HTML Source Code
     * @param   string $tag  HTML Tag
     *
     * @return  string
     */
    function extract_tag($html, $tag = 'div')
    {
        $html = preg_match_all("/(\<" . $tag . ")(.*?)(" . $tag . ">)/si", $html, $matches);

        $result = '';
        foreach ($matches[ 0 ] as $item) {
            $result = preg_replace("/\<[\/]?" . $tag . "\>/", '', $item);
        }

        return $result;
    }
}