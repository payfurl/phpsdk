<?php
namespace payFURL\Sdk;

/*
 * (c) payFurl
 */
class ArrayTools
{
    static function CleanEmpty($Array)
    {
        foreach ($Array as $key => $value)
        {
            if (is_array($value))
            {
                $Array[$key] = ArrayTools::CleanEmpty($value);
            }
            else
            {
                if (empty($value))
                {
                    unset($Array[$key]);
                }
            }
        }
        return $Array;
    }

    static function ValidateKeys($Parameters, $RequiredParameters)
    {
        foreach ($RequiredParameters as $i => $value)
        {
            if (!array_key_exists($value, $Parameters))
            {
                throw new ResponseException('"' . $value . "' is required", 0);
            }
            
            if (is_null($Parameters[$value]))
            {
                throw new ResponseException('"' . $value . "' is required", 0);
            }
        }        
    }
}