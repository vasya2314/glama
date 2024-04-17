<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

final class Core
{
    protected static ?self $instance = null;

    private function __construct()
    {
        $this->disableWrappingJSON();
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function disableWrappingJSON(): void
    {
        JsonResource::withoutWrapping();
    }

}
