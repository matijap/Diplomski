<?php
$firstNames = array('John', 'Jack', 'Jill');
$lastNames  = array('Jonhson', 'Smith', 'Anderson');
// $array = array('first_name' => 'Matija',
//                'last_name'  => 'Parausic');
$nameRand = rand ( 0 , 2 );
$lastNameRand = rand ( 0 , 2 );
$array = array('first_name' => $firstNames[$nameRand], 'last_name' => $lastNames[$lastNameRand]);
$encode = json_encode($array);

echo $encode;