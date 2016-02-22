<?php

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 20:56
 */
class ActiveController extends BaseController
{

    public function index()
    {

    }

    public function randomResult()
    {
        $rand = rand(1, 4);
        echo 'The winner is number ' . $rand . PHP_EOL;
        switch ($rand) {
            case 1:
                return true;
            case 2:
                return false;
            case 3:
                throw new Exception('Unexpected situation');
            case 4:
                $micro_seconds = rand(1000000, 4000000);
                echo 'Going to wait for some time' . PHP_EOL;
                usleep($micro_seconds);
                return true;
        }
        return false;
    }
}
