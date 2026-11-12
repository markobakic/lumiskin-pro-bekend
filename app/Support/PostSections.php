<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

/**
 * Struktura sadržaja blog posta (kolona `posts.sections`, JSON).
 *
 * sections: [
 *   { type: 'takeaways', heading, items: string[] },                 // "Ključni zaključci" box
 *   {
 *     heading: string,                                                // naslov sekcije (h2, ide u sadržaj)
 *     image?: string,                                                 // stara "slika sekcije" (prikazuje se ispod naslova)
 *     source?: { text, href },
 *     paragraphs: Block[],                                            // blokovi bilo kojim redom
 *   }
 * ]
 *
 * Block:
 *   "tekst"                                                           // paragraf (string — stari format, i dalje važi)
 *   { type: 'list', items: string[] }
 *   { type: 'subheading', text }                                      // podnaslov (h3)
 *   { type: 'image', url, alt?, caption? }                            // slika bilo gde u tekstu
 *
 * normalize() odbacuje prazne/nepoznate blokove i baca ValidationException za neispravan URL slike.
 */
class PostSections
{
    public static function normalize(?array $sections): array
    {
        if (! $sections) {
            return [];
        }

        $out = [];
        foreach (array_values($sections) as $i => $section) {
            if (! is_array($section)) {
                continue;
            }

            if (($section['type'] ?? null) === 'takeaways') {
                $items = self::strings($section['items'] ?? []);
                if ($items) {
                    $out[] = [
                        'type'    => 'takeaways',
                        'heading' => self::str($section['heading'] ?? '') ?: 'Ključni zaključci',
                        'items'   => $items,
                    ];
                }
                continue;
            }

            $row = [
                'heading'    => self::str($section['heading'] ?? ''),
                'paragraphs' => self::blocks($section['paragraphs'] ?? [], "sections.$i.paragraphs"),
            ];

            if (! empty($section['image']) && is_string($section['image'])) {
                $row['image'] = self::url($section['image'], "sections.$i.image");
            }

            $href = self::str($section['source']['href'] ?? '');
            if ($href !== '') {
                $row['source'] = [
                    'text' => self::str($section['source']['text'] ?? '') ?: $href,
                    'href' => self::url($href, "sections.$i.source.href"),
                ];
            }

            $out[] = $row;
        }

        return $out;
    }

    private static function blocks(mixed $blocks, string $path): array
    {
        if (! is_array($blocks)) {
            return [];
        }

        $out = [];
        foreach (array_values($blocks) as $j => $block) {
            if (is_string($block)) {
                $text = self::str($block);
                if ($text !== '') {
                    $out[] = $text;
                }
                continue;
            }
            if (! is_array($block)) {
                continue;
            }

            switch ($block['type'] ?? null) {
                case 'paragraph':
                    $text = self::str($block['text'] ?? '');
                    if ($text !== '') {
                        $out[] = $text;
                    }
                    break;

                case 'list':
                    $items = self::strings($block['items'] ?? []);
                    if ($items) {
                        $out[] = ['type' => 'list', 'items' => $items];
                    }
                    break;

                case 'subheading':
                    $text = self::str($block['text'] ?? '');
                    if ($text !== '') {
                        $out[] = ['type' => 'subheading', 'text' => mb_substr($text, 0, 255)];
                    }
                    break;

                case 'image':
                    $url = self::str($block['url'] ?? '');
                    if ($url !== '') {
                        $img = ['type' => 'image', 'url' => self::url($url, "$path.$j.url")];
                        $alt = self::str($block['alt'] ?? '');
                        $caption = self::str($block['caption'] ?? '');
                        if ($alt !== '') {
                            $img['alt'] = mb_substr($alt, 0, 255);
                        }
                        if ($caption !== '') {
                            $img['caption'] = mb_substr($caption, 0, 500);
                        }
                        $out[] = $img;
                    }
                    break;
            }
        }

        return $out;
    }

    private static function str(mixed $v): string
    {
        return is_string($v) || is_numeric($v) ? trim((string) $v) : '';
    }

    private static function strings(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter(array_map([self::class, 'str'], $items), fn ($s) => $s !== ''));
    }

    /** Dozvoljeni su samo http(s) URL-ovi i putanje sa sajta ("/blog/x.jpg") — bez javascript: i sl. */
    private static function url(string $url, string $path): string
    {
        $url = trim($url);
        $isLocalPath = preg_match('#^/[^/\\\\]#', $url) === 1 && ! preg_match('/\s/', $url);
        $isHttp = preg_match('#^https?://#i', $url) === 1 && filter_var($url, FILTER_VALIDATE_URL) !== false;
        if (! $isLocalPath && ! $isHttp) {
            throw ValidationException::withMessages([$path => 'Neispravan URL: ' . mb_substr($url, 0, 80)]);
        }

        return $url;
    }
}
