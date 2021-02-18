<?php

namespace App\DTO;

class GameScreenshot
{
    /**
     * @var string
     */
    public string $preview_url;

    /**
     * @var string
     */
    public string $url;

    /**
     * @param string $preview_url
     * @param string $url
     *
     * @return void
     */
    public function __construct(string $preview_url, string $url)
    {
        $this->preview_url = $preview_url;
        $this->url = $url;
    }
}
