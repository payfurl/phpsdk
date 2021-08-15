<?php
namespace payFURL\Sdk;

/*
 * (c) payFurl
 */
class UrlTools
{
    static function AddItem($querystring, $Key, $Value)
    {
        if ($querystring == "")
        {
            $querystring = "?";
        }
        else
        {
            $querystring = $querystring . "&";
        }

        return $querystring . $Key . "=" . urlencode($Value);
    }

    static function CreateQueryString($Array)
    {
        $querystring = "";
        foreach ($Array as $Key => $Value) {
            $querystring = $this->AddItem($querystring, $Key, $Value);
        }
        return $querystring;
    }
}