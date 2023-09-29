<?php

final class DemoFile
{
    public function run(bool $param)
    {
        if ($this->isTrue($param)) {
            return 5;
        }

        return '10';
    }

    private function isTrue($value)
    {
        return $value === true;
    }
}
