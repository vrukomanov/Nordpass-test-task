<?php

namespace App\Prototypes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BasicController extends AbstractController
{

    /**
     * @param $input
     * @return mixed|string
     */
    public function sanitize($input){
        if(!is_numeric($input) && !is_string($input)){
           return $input;
        }
        return htmlentities($input);
    }

    /**
     * @param $input
     * @return array
     */
    public function parseRawHttpRequest($input): array
    {
        $result = [];
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        $boundary = $matches[1];

        $requestBlocks = preg_split("/-+$boundary/", $input);
        array_pop($requestBlocks);

        foreach ($requestBlocks as $id => $block)
        {
            if (empty($block)){
                continue;
            }

            if (strpos($block, 'application/octet-stream') !== FALSE) {
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            } else {
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
            }

            if(!isset($matches[1]) || !isset($matches[2])){
                continue;
            }

            $result[$matches[1]] = $matches[2];
        }

        return $result;
    }

}