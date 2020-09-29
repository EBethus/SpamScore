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
        $text = preg_replace('/[\d=|#@$\-]/mi', '', $text);
        $text = preg_replace('/\s{2,}/mi', '', $text);
        $text = preg_replace('/\v/mi', '', $text);
        $keywords = json_decode(file_get_contents(resource_path('spam.json')));
        $master = strtolower(strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8')));
        $total = 0;
        $counter = [];
        $words = str_word_count($master);
        $matchs = 0;
        foreach ($keywords as $keyword => $w) {
            $count = substr_count($master, $keyword);
            $matchs += ($count * str_word_count($keyword));
            $total += ($w ** $count - 1);
            if ($count > 1) {
                $counter[$keyword] = $count;
            }
        }

        $perc = ($matchs / $words) *100;
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
