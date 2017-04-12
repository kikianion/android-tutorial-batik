<?php
/**
 * Created by PhpStorm.
 * User: super1
 * Date: 12/04/2017
 * Time: 18:37
 */


function sort_($obj, $item)
{
    for ($i = 0; $i < count($obj); $i++) {
        for ($j = $i+1; $j < count($obj); $j++) {
            if ($i != $j) {
                $order1=$obj[$i];
                $order2=$obj[$j];

                $orderBy1=$order1[$item];
                if (isset($order1[$item]) && ($order1[$item] > $order2[$item])) {
                    $obj1 = $obj[$j];
                    $obj[$j] = $obj[$i];
                    $obj[$i] = $obj1;
                }
            }
        }

    }
    return $obj;
}
