<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->posts() as $data) {
            Post::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }

    /** Mirrors src/content/posts.js. */
    private function posts(): array
    {
        return [
            [
                'slug' => 'suva-koza-lica',
                'title' => 'Suva koža lica: zašto nastaje i šta zaista pomaže',
                'excerpt' => 'Suva koža nije dijagnoza nego stanje zaštitnog sloja. Evo kako da prepoznaš uzroke i pojednostaviš negu.',
                'date' => '2026-09-02',
                'reading_time' => 5,
                'is_published' => true,
                'sections' => [
                    [
                        'heading' => 'Šta znači da je koža suva',
                        'paragraphs' => [
                            'Koža je suva kad proizvodi manje sopstvenih masnoća nego što joj treba. Zaštitni sloj tada popušta, voda lakše ishlapljuje i javlja se zatezanje, perutanje i dosadno crvenilo.',
                            'Najčešće se meša sa dehidriranom kožom, koja ne mora da bude suva po tipu, nego samo ostaje bez vode zbog grejanja, vetra ili prejakih proizvoda.',
                        ],
                    ],
                    [
                        'heading' => 'Kako da je umiriš',
                        'paragraphs' => [
                            'Počni od čišćenja. Blag gel i mlaka voda uvek su bolji izbor od proizvoda koji ostavlja osećaj škripe.',
                            'Zatim dodaj hidrataciju i kremu koja zadržava vlagu. Pojednostavi rutinu: manje proizvoda, više doslednosti, i prati kako koža reaguje nedelju dana pre nego što išta menjaš.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'nega-lica-zimi',
                'title' => 'Nega lica zimi: vodič za kožu koja se suši od grejanja',
                'excerpt' => 'Hladan vetar napolju, suv vazduh unutra. Kako da prilagodiš rutinu kad temperatura padne.',
                'date' => '2026-09-16',
                'reading_time' => 4,
                'is_published' => true,
                'sections' => [
                    [
                        'heading' => 'Zašto zima menja kožu',
                        'paragraphs' => [
                            'Hladan vazduh sadrži manje vlage, a grejanje je dodatno isušuje. Koža tada brže gubi vodu, pa ono što je leti radilo savršeno zimi odjednom ne zadovoljava.',
                        ],
                    ],
                    [
                        'heading' => 'Tri promene koje se isplate',
                        'paragraphs' => [
                            'Prvo, bogatija krema uveče. Drugo, kraći i hladniji tuševi. Treće, ovlaživač vazduha u prostoriji u kojoj najviše boraviš.',
                            'Kratak jutarnji ritual hlađenja može da bude dobar način da razbudiš lice, ali zimi ga uvek završi hidratantnom kremom.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'bubuljice-po-zonama',
                'title' => 'Bubuljice po zonama lica: šta mesto pojavljivanja govori',
                'excerpt' => 'Bubuljice retko iskaču bez razloga. Pogledaj šta čelo, nos, obrazi i vilica mogu da ti kažu o navikama.',
                'date' => '2026-09-30',
                'reading_time' => 6,
                'is_published' => true,
                'sections' => [
                    [
                        'heading' => 'Mapa nije dijagnoza',
                        'paragraphs' => [
                            'Zone lica mogu da ukažu na navike, ali ne zamenjuju pregled kod dermatologa. Uzmi ih kao ideju gde da tražiš uzrok, a ne kao pravilo.',
                        ],
                    ],
                    [
                        'heading' => 'Šta možeš da proveriš sama',
                        'paragraphs' => [
                            'Čelo i linija kose često reaguju na proizvode za kosu i kapu. Obrazi na jastučnicu i telefon. Vilica na hormonske promene i stres.',
                            'Menjaj jednu naviku odjednom i beleži rezultate. Tako ćeš znati šta je zaista pomoglo, umesto da pogađaš.',
                        ],
                    ],
                ],
            ],
        ];
    }
}
