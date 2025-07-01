<?php

/**
 * Escape HTML special characters in a string
 *
 * This function converts special characters to HTML entities to prevent XSS attacks.
 *
 * @param string $str The input string to escape
 * @return string The escaped string
 */
function htmlEscape(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}