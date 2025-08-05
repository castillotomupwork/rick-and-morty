<?php

namespace App\Helper;

class ParseHelper
{
    /**
     * Converts specific fields in the input array to integers.
     * Fields not listed in `$fields` remain unchanged.
     * Empty values in targeted fields are set to 0.
     *
     * @return array
     */
    public function requestToInteger(array $params, array $fields): array
    {
        $newVar = [];
        foreach ($params as $key => $val) {
            if (in_array($key, $fields)) {
                if (empty($val)) {
                    $newVar[$key] = 0;
                } else {
                    $newVar[$key] = (int) $val;
                }
            } else {
                $newVar[$key] = $val;
            }
        }

        return $newVar;
    }
}
