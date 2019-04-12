<?php

namespace System\View;

interface ViewInterface
{
    public function getOutput();
    public function __toString();
}