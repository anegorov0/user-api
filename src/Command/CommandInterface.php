<?php

namespace App\Command;

interface CommandInterface{
    public function execute(array $args): void;
}