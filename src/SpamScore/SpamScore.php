<?php
namespace EBethus\SpamScore;

class SpamScore
{
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function score($text)
    {
        $keywords = json_decode(file_get_contents(resource_path('spam.json')));
        $master = strtolower(strip_tags($value));
        $total = 0;
        foreach ($keywords as $keyword => $w) {
            $total += ($w ** substr_count($master, $keyword)-1);
        }

        return $total;
    }
}

