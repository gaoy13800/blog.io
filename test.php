<?php
/**
 * Created by PhpStorm.
 * User: Gaoy
 * Date: 2018/4/13
 * Time: 15:40
 */


$orderNum = "f9dfyg9d9gf9dgudf9gu9gr9";

$orderNum = $orderNum . 'single' . rand(1,100);


$result = explode($orderNum, 'single');;





if (strpos($orderNum, 'single') !== false){
    var_dump($index =  substr($orderNum, 0, strpos($orderNum, 'single')) );
}

