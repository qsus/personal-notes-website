<?php

declare(strict_types=1);

abstract class Controller
{
    abstract public function run(Request $r): Response;
}
