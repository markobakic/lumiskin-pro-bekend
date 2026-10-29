<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->products() as $data) {
            $data['details'] = $this->details($data['slug']);
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }

    /** Full detail blob from database/seeders/data/<slug>.json (copied from the Next.js site). */
    private function details(string $slug): ?array
    {
        $path = database_path("seeders/data/{$slug}.json");
        if (! File::exists($path)) {
            return null;
        }

        return json_decode(File::get($path), true);
    }

    /** Base listing fields — mirrors src/content/products.js. */
    private function products(): array
    {
        return [
            [
                'slug' => 'face-mask-4d',
                'category' => 'lice',
                'num' => '01',
                'name' => '4D LumiSkin Pro maska',
                'subtitle' => 'Najprodavaniji LED tretman za lice',
                'description' => 'Naša najnaprednija LED maska za intenzivniju obnovu kože – namenjena ublažavanju finih linija, akni, hiperpigmentacije i poboljšanju teksture kože.',
                'description_long' => '4D LumiSkin Pro je LED maska namenjena kućnoj nezi lica, osmišljena da podrži obnovu kože i poboljša njen izgled primenom svetlosne terapije sa 9 različitih svetlosnih režima i 4 talasne dužine.',
                'features' => [
                    'Crvena (630 nm) – ublažava fine linije i bore',
                    'Bliska infracrvena (850 nm) – podržava sintezu kolagena',
                    'Plava (465 nm) – smanjuje pojavu akni',
                    'Zelena (530 nm) – ujednačava ten',
                    '280 LED dioda, 9 svetlosnih režima',
                    'Medicinski silikon, automatsko isključivanje',
                ],
                'price' => 31600,
                'accent' => 'rgba(232, 67, 67, 0.12)',
                'accent_strong' => '#E84343',
                'use_img' => true,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'glow-therapy-3d',
                'category' => 'lice',
                'num' => '02',
                'name' => '3D Glow Therapy maska',
                'subtitle' => 'LED tretman za negu lica',
                'description' => 'LED maska za kućnu negu lica sa 280 LED dioda i 8 režima svetlosne terapije za različite potrebe kože.',
                'description_long' => '3D Glow Therapy maska osmišljena je za jednostavnu kućnu negu lica uz pomoć svetlosne terapije. Fleksibilna silikonska konstrukcija, 280 LED dioda i 8 režima svetlosti omogućavaju tretman prilagođen vašim potrebama.',
                'features' => [
                    '280 LED dioda za svetlosnu negu lica',
                    '8 režima svetlosne terapije',
                    'Talasne dužine: 465nm, 530nm, 590nm, 630nm i 850 nm',
                    'Fleksibilna silikonska konstrukcija',
                    'Tretmani od 5 ili 10 minuta',
                    'Upravljanje putem kontrolera',
                ],
                'price' => 25750,
                'accent' => 'rgba(165, 110, 200, 0.12)',
                'accent_strong' => '#a56ec8',
                'use_img' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'slug' => 'neck-decollete',
                'category' => 'vrat',
                'num' => '03',
                'name' => '4D LumiSkin Pro Vrat & Dekolte',
                'subtitle' => 'Specijalizovana LED maska za vrat i dekolte',
                'description' => 'Vrat i dekolte su zone koje prve pokazuju znakove starenja, a često se zanemaruju u rutini nege. Specijalno oblikovana da prati anatomiju ovih zona, pružajući ciljani LED tretman tamo gde je najpotrebniji.',
                'description_long' => 'Vrat i dekolte su zone koje prve pokazuju znakove starenja, a često se zanemaruju u rutini nege. 4D LumiSkin Pro Vrat & Dekolte maska je specijalno oblikovana da prati anatomiju ovih zona, pružajući ciljani LED tretman tamo gde je najpotrebniji.',
                'features' => [
                    'Anatomski prilagođen oblik za vrat i dekolte',
                    '504 LED dioda',
                    'Medicinski silikon visokog kvaliteta',
                    '9 režima svetlosne terapije, 3 nivoa intenziteta',
                    'LED diode sa zlatnim žičanim spojevima',
                ],
                'price' => 29250,
                'accent' => 'rgba(212, 168, 168, 0.18)',
                'accent_strong' => '#d4a8a8',
                'use_img' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'slug' => 'led-care-cap',
                'category' => 'kosa',
                'num' => '04',
                'name' => 'LumiSkin Pro LED Care Cap',
                'subtitle' => 'LED kapa za kosu i teme',
                'description' => 'LED kapa koja kombinuje crvenu svetlost od 660 nm i blisku infracrvenu svetlost od 850 nm za neinvazivnu svetlosnu negu temena glave.',
                'description_long' => 'LumiSkin Pro LED Care Cap je LED kapa namenjena kućnoj nezi temena, koja kombinuje dve talasne dužine svetlosti uz tri nivoa intenziteta i četiri režima rada. Praktična za svakodnevnu rutinu nege kose kod kuće.',
                'features' => [
                    'Crvena svetlost 660 nm — nega folikula dlake',
                    'Bliska infracrvena 850 nm — dopunska nega temena',
                    'Tri nivoa intenziteta',
                    'Četiri režima: pulsiranje i kontinuirano',
                    'Automatsko isključivanje nakon 20 min',
                    'USB punjenje, 5 V / 1 A',
                ],
                'price' => 29250,
                'accent' => 'rgba(200, 130, 100, 0.12)',
                'accent_strong' => '#c88264',
                'use_img' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];
    }
}
