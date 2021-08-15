<?php
namespace payFURL\Sdk;

/*
 * (c) payFurl
 */
class ArrayTools
{
    static function CleanEmpty($Array)
    {
        foreach ($Array as $key => $value) {
            if (is_array($value)) {
                $Array[$key] = ArrayTools::CleanEmpty($value);
            } else {
                if (empty($value)){
                    unset($Array[$key]);
                }
            }
        }
        return $Array;
    }
}