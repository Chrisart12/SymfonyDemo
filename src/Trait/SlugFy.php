<?php 

namespace App\Trait;

use Symfony\Component\String\Slugger\AsciiSlugger;

trait SlugFy 
{
    public function makeSlgug(string $text)
    {
        $slugger = new AsciiSlugger();
        return strtolower($slugger->slug($text));
    }
}


