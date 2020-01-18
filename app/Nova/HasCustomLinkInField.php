<?php

namespace App\Nova;

trait HasCustomLinkInField
{
    /**
     * Generate a link with that uses nova's default styles.
     *
     * @param string      $href
     * @param string|null $text
     * @param string      $target
     *
     * @return string
     */
    protected function generateLink(string $href, string $text = '', string $target = '_self'): string
    {
        $display = $text ?: $href;

        return "<a class='no-underline font-bold dim text-primary' href='{$href}' target='{$target}'>{$display}</a>";
    }
}