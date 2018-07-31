<?php

namespace app\controllers;

use mako\http\routing\Controller;

abstract class BaseController extends Controller
{
    protected function passFieldErrors(array $errors = [])
    {
        $this->session->putFlash('errors', $errors);
    }

    protected function getFieldErrors(): array
    {
        return $this->session->getFlash('errors', []);
    }
}