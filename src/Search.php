<?php

/**
 * Created by PhpStorm.
 * User: chell_uoxou
 * Date: 2016/12/30
 * Time: 午後 8:48
 */
class Search extends PrimeNumber
{
    public function startSearch($to,$doDisplayInfo)
    {
        $a = $to;

        $this->progressObj = new AllProgressBar();
        $progress = $this->progressObj->create_progress(floor($a / 2));

//        echo $a;
//
//        var_dump($doDisplayInfo);

        printf("\033[2J");

        for ($b = 3; $b <= $a; $b = $b + 2) {
            printf("\033[%d;%dH" ,1,1);

            $result = $this->isPrimeNumber($b,$doDisplayInfo);

            if ($result === true) {
                if ($doDisplayInfo) {
                    echo PHP_EOL . $progress();
                    echo PHP_EOL . $b . " - [" . $this->primeNumberCount ."]/" . $to . ": It is a prime number.";
                    printf("\033[%dA" ,2);
                    printf("\033[1K");
                }
                $this->primeNumberCount++;
            } else {
                if ($doDisplayInfo) {
                    echo PHP_EOL . $progress();
                    echo PHP_EOL . $b . " - [" . $this->primeNumberCount ."]/" . $to . ": It isn't a prime number. \nIt is divisible by " . $result . ".";
                    printf("\033[%dA" ,3);
                    printf("\033[1K");
                }
            }
        }
        return array(
            "prime_numbers_count" => $this->primeNumberCount
        );
    }
}