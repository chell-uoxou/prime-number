<?php
/**
 * Created by PhpStorm.
 * User: chell_uoxou
 * Date: 2016/12/30
 * Time: 午後 8:44
 */

include_once "src/PrimeNumber.php";
include_once "src/EachProgressBar.php";
include_once "src/Search.php";

echo "[?] Calculate mode ( [t]o limit | [i]s prime) :";

$input = strtolower(trim(fgets(STDIN)));
$mode = $input;


if ($mode != "i" and $mode != "t") {
    echo "[!] Please enter \"i\" or \"t\" .";
    exit(0);
}

switch ($mode) {
    case "t":
        toLimitMode();
        break;

    case "i":
        isPrimeMode();
        break;
}


function toLimitMode()
{

    echo "[?] Calculation limit (int):";

    $input = trim(fgets(STDIN));
    $toNumber = $input;
    if (!is_int((int)$toNumber)) {
        echo "[!] Please enter integral number.";
        exit(0);
    }


    echo "[?] Do display information (Y/n) :";

    $input = strtolower(trim(fgets(STDIN)));

    if ($input != "y" and $input != "n") {
        echo "[!] Please enter \"y\" or \"n\" .";
        exit(0);
    }

    $doDisplayInfo = ($input == "y");

    $object = new PrimeNumber();
    $result_array = $object->startToLimitMode($toNumber, $doDisplayInfo);

    $count = $result_array["prime_numbers_count"];
    printf("\033[%d;%dH" ,3,1);
    echo "[*] There are " . $count . " prime numbers.\n";

}

function isPrimeMode()
{

}