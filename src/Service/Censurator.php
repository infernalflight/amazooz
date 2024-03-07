<?php

namespace App\Service;

class Censurator
{

    public function purify(string $text): string
    {
        $file = '../data/unwanted_words.txt';

        $unwantedWords = file($file);

        foreach ($unwantedWords as $word) {
            $word = str_ireplace(PHP_EOL, '', $word);
            $replacement = str_repeat('*', strlen($word));
            $text = str_ireplace($word, $replacement, $text);
        }

        return $text;
    }
}