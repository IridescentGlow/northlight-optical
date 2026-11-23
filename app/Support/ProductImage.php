<?php

namespace App\Support;

/**
 * Product photos are 1000 px wide. Smaller copies sit next to each one as
 * name-320w.jpg and name-600w.jpg, so an <img> can list all three in srcset
 * and the browser takes the smallest that is sharp enough for the slot.
 * A photo without copies (one added later) just gets its own width listed.
 */
class ProductImage
{
    public const WIDTHS = [320, 600];

    /** @var array<string, string> */
    private static array $cache = [];

    /** srcset value for a path relative to storage/app/public, e.g. "images/sunglasses1.jpg". */
    public static function srcset(string $path): string
    {
        return self::$cache[$path] ??= self::build($path);
    }

    private static function build(string $path): string
    {
        $file = storage_path('app/public/'.$path);
        $size = is_file($file) ? @getimagesize($file) : false;

        if ($size === false) {
            return '';
        }

        $base = substr($path, 0, -strlen('.'.pathinfo($path, PATHINFO_EXTENSION)));
        $sources = [];

        foreach (self::WIDTHS as $width) {
            if ($width < $size[0] && is_file(storage_path("app/public/{$base}-{$width}w.jpg"))) {
                $sources[] = asset("storage/{$base}-{$width}w.jpg")." {$width}w";
            }
        }

        $sources[] = asset('storage/'.$path)." {$size[0]}w";

        return implode(', ', $sources);
    }
}
