<?php
namespace payFURL\Sdk;

/*
 * (c) payFurl
 */
final class ArrayTools
{
    private static function CleanEmpty($Array)
    {
        foreach ($Array as $key => $value) {
            if (is_array($value)) {
                $Array[$key] = $this->CleanEmpty($value);
            } else {
                if (empty($value)){
                    unset($Array[$key]);
                }
            }
        }
        return $Array;
    }
}