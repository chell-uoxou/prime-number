<?php


class PrimeNumber
{

    public $doDisplayInfo;
    public $primeNumberCount;

    public $progressObj;
    public $searchObj;

    function __construct()
    {

    }

    public function startToLimitMode($toNumber, $display)
    {
        date_default_timezone_set('Asia/Tokyo');

        include_once "Search.php";
        include_once "EachProgressBar.php";
        include_once "AllProgressBar.php";

        $this->searchObj = new Search();
        $this->progressObj = new EachProgressBar();

        $doDisplayInfo = $display;

//        echo "1:";
//        var_dump($doDisplayInfo);

        $return = $this->searchObj->startSearch($toNumber,$doDisplayInfo);

        return $return;
    }

    public function isPrimeNumber($var,$doDisplayInfo)
    {
        $this->progressObj = new EachProgressBar();
        $progress = $this->progressObj->create_progress($var);

        $scanCount = floor(sqrt($var));

        $return = true;

        $i = 1;

//        echo "ipn:";
//        var_dump($doDisplayInfo);

        while ($i <= $scanCount) {
            $i++;
            $result = $var % $i;

            if ($doDisplayInfo) {
                echo $progress();
            }

            if ($result === 0) {
                $return = $i;
                break;
            }
        }
        return $return;
    }
}

