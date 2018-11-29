<?php

namespace Core\Mvc;

interface IActionResult {
    public function execute() : string;
}