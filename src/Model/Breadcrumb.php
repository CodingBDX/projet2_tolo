<?php

namespace App\Model;

class Breadcrumb
{
    public function makeBreadCrumbs($separator = '/')
    {
        // extract uri path parts into an array
        $breadcrumbs = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

        // determine the base url or domain
        $base = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];

        $last = end($breadcrumbs); // obtain the last piece of the path parts
        $crumbs['Home'] = $base; // Our first crumb is the base url
        $current = $crumbs['Home']; // set the current breadcrumb to base url

        // create valid urls from the breadcrumbs and store them in an array
        foreach ($breadcrumbs as $key => $piece) {
            // ignore file names and create urls from  directory path
            if (false == strstr($last, '.php')) {
                $current = $current.$separator.$piece;
                $crumbs[$piece] = $current;
            } else {
                if ($piece !== $last) {
                    $current = $current.$separator.$piece;
                    $crumbs[$piece] = $current;
                }
            }
        }

        $links = '';
        $count = 0;

        // create html tags for displaying the breadcrumbs
        foreach ($crumbs as $key => $value) {
            $x = array_filter(explode('/', parse_url($value, PHP_URL_PATH)));
            $last = end($x);
            // this will add a class to the last link to control its appearance
            $clas = ($count === count($crumbs) - 1 ? ' current-crumb' : '');

            // determine where to print separators
            $sep = ($count > -1 && $count < count($crumbs) - 1 ? '' : '');

            $links .= "<a class=\"breadcrumb-item  {$clas}\" href=\"{$value}\">{$key}</a> {$sep}";
            ++$count;
        }

        return $links;
    }
}
