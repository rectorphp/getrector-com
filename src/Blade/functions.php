<?php

declare(strict_types=1);

function clearTitle(string $title): string {
    $clearTitle = strip_tags($title);
    return str_replace('&nbsp;', ' ', $clearTitle);
}

