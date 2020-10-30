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
        $text = preg_replace('/[=“‴|#@$\.\+\-]/mi', '', $text);
        $text = preg_replace('/\v/mi', '', $text);
        $text = preg_replace('/\s{2,}/mi', '', $text);
        $keywords = json_decode(file_get_contents(resource_path('spam.json')));
        $master = strtolower(strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8')));
        //echo $master;
        preg_match_all('/\d{4,}/i', $master, $numbers, PREG_SET_ORDER);

        $matchs = 0;
        $words = 0;
        $total_number = 0;
        if ($numbers && $numbers[0]) {
            $matchs = count($numbers[0]) * 10;
            $total_number = count($numbers[0]);
        }

        $total = 0;
        $counter = [];
        $words = max(0, str_word_count($master)-$total_number);

        foreach ($keywords as $keyword => $w) {
            $count = substr_count($master, $keyword);
            $matchs += ($count * str_word_count($keyword));
            $total += ($w ** $count)-1;
            if ($count > 1) {
                $counter[$keyword] = $count;
            }
        }

        $perc = $words > 0 ? ($matchs / $words) *100 : 0;
        $factor = 1;
        if ($perc >= 70) {
            $factor = 10;
        } elseif ($perc > 60) {
            $factor = 8;
        } elseif ($perc > 45) {
            $factor = 5;
        } elseif ($perc > 35) {
            $factor = 2;
        } elseif ($perc > 25) {
            $factor = 1.8;
        } elseif ($perc > 15) {
            $factor = 1.6;
        }
        return $factor * $total;
    }
}
