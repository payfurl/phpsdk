<?php
namespace payFURL\Sdk;

/*
 * (c) payFURL
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

    static function CreateQueryString($QueryParameters, $ValidParameters)
    {
        // check keys are valid
        foreach ($QueryParameters as $Key => $Value)
        {
            if (!in_array(strtolower($Key), $ValidParameters))
            {
                throw new \Exception("Invalid Parameter: " . $Key);
            }
        }

        $querystring = "";
        foreach ($QueryParameters as $Key => $Value) {
            $querystring = UrlTools::AddItem($querystring, $Key, $Value);
        }
        return $querystring;
    }
}