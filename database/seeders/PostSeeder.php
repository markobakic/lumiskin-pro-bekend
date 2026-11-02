<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::query()->delete();

        foreach ($this->posts() as $data) {
            Post::create($data);
        }
    }

    private function posts(): array
    {
        return [

            // ══════════════════════════════════════════════════════════════
            // BLOG 1
            // ══════════════════════════════════════════════════════════════
            [
                'slug'         => 'razlicite-boje-led-terapije-istovremeno',
                'title'        => 'Mogu li istovremeno da koristim različite boje LED svetlosne terapije?',

                // Višeparagrafni uvod — separisano sa \n\n
                'excerpt' => implode("\n\n", [
                    'Retko se dešava da se suočavamo samo sa jednim problemom kože, a mnogi od nas imaju i kombinovane tipove kože. Pojedini LED uređaji poseduju više talasnih dužina svetlosti i dizajnirani su tako da istovremeno odgovore na različite potrebe kože.',
                    'To uključuje crvenu svetlost za anti-age negu, plavu za kožu sklonu aknama, zelenu za hiperpigmentaciju, žutu za crvenilo i druge svetlosne režime.',
                    'Način na koji kombinujemo ove tretmane važan je za postizanje bržih i efikasnijih rezultata.',
                ]),

                'date'         => '2026-10-30',
                'reading_time' => 7,
                'is_published' => true,

                'sections' => [

                    // Ključni zaključci
                    [
                        'type'    => 'takeaways',
                        'heading' => 'Ključni zaključci',
                        'items'   => [
                            'Različite boje LED svetlosti mogu se koristiti istovremeno.',
                            'Korišćenje različitih boja LED svetlosti u isto vreme ne poništava efekte pojedinačnih tretmana.',
                            'Korišćenje različitih boja uzastopno može čak doprineti boljim rezultatima.',
                            'Uvek se pridržavajte ukupnog preporučenog trajanja tretmana za vaš uređaj.',
                        ],
                    ],

                    // § 01 ─────────────────────────────────────────────────
                    [
                        'heading'    => 'Koje su različite boje LED svetlosne terapije?',
                        'paragraphs' => [],
                    ],

                    // § 02 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Koja boja je najbolja za svetlosnu terapiju?',
                        'paragraphs' => [
                            'Crvena svetlost se često smatra zlatnim standardom LED svetlosne terapije. U spektru vidljive svetlosti, crvena svetlost ima najdužu talasnu dužinu i prodire u kožu, pružajući podršku stvaranju novog kolagena i obnovi kože.',
                            'Crvena svetlost ima široku primenu jer, osim što podržava procese obnove kože, može pomoći u ublažavanju upalnih procesa, bola i crvenila. Zbog toga se široko koristi i u oblasti velnesa i nege.',
                            'Ulaganje u uređaj koji kombinuje više boja svetlosti omogućava vam da prilagodite tretman svom tipu kože i njenim specifičnim potrebama, a da pritom zadržite sve prednosti tradicionalne terapije crvenom svetlošću u anti-age nezi.',
                            'Većina uređaja sadrži crvenu svetlost, dok pojedini nude i dodatne režime za sveobuhvatniji tretman.',
                        ],
                    ],

                    // § 03 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Da li se različite boje LED svetlosti mogu koristiti istovremeno?',
                        'paragraphs' => [
                            'Ne postoje kontraindikacije za istovremeno korišćenje više talasnih dužina LED svetlosti, a uzastopni tretmani neće poništiti efekte prethodnih. Svaka boja svetlosti deluje na različitim dubinama kože, čime može doprineti ukupnim efektima tretmana.',
                            'Kada je reč o kombinovanju LED svetlosti, postoje dve mogućnosti:',
                            [
                                'type'  => 'list',
                                'items' => [
                                    'Maska sa LED diodama različitih boja raspoređenim na određenim mestima.',
                                    'Maska koja nudi jednu talasnu dužinu svetlosti po režimu rada, ali ima više dostupnih režima, tako da se različite boje mogu koristiti jedna za drugom tokom istog tretmana (na primer, 3 minuta crvene svetlosti, nakon čega slede 3 minuta plave svetlosti).',
                                ],
                            ],
                        ],
                    ],

                    // § 04 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Koja opcija je bolja?',
                        'paragraphs' => [
                            'LED maska koja nudi jednu talasnu dužinu svetlosti po režimu rada pruža znatno sveobuhvatniji i fleksibilniji pristup tretmanu.',
                            'Ukoliko su LED diode raspoređene na način prikazan na prvoj slici iznad, određene regije lica, poput područja oko očiju ili brade, možda neće dobiti tretman crvenom svetlošću.',
                            'S obzirom na to da se potrebe naše kože mogu menjati iz dana u dan, maska sa više svetlosnih režima pruža veću fleksibilnost.',
                            'U suštini, malo je verovatno da će istim delovima lica uvek biti potreban potpuno isti tretman. Maska sa više režima omogućava vam da odaberete dva do tri uzastopna tretmana, u skladu sa trenutnim potrebama kože. Maska koja ima samo jedan režim, u kojem se sve boje koriste istovremeno, ne pruža isti nivo mogućnosti prilagođavanja.',
                            'Pojedinih dana možda ćete želeti da se više fokusirate na stvaranje kolagena. U tom slučaju, režim crvene i infracrvene svetlosti delovaće na celo lice, umesto samo na određene regije.',
                        ],
                    ],

                    // § 05 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Mogu li kombinovati terapiju crvenom i plavom svetlošću?',
                        'paragraphs' => [
                            'Crvena i plava svetlost spadaju među najistraživanije talasne dužine LED svetlosne terapije. Postoje značajni naučni dokazi koji ukazuju na to da kombinacija crvene i plave, kao i crvene i infracrvene svetlosti, može biti efikasnija od same plave svetlosti u tretiranju akni i sprečavanju njihove pojave.',
                            'Jedna studija objavljena u časopisu Journal of Drugs in Dermatology zabeležila je smanjenje broja akoznih promena za 76% nakon samo 12 nedelja primene kombinovane terapije crvenom i plavom svetlošću.',
                            'Maska 4D LumiSkin Pro koristi četiri talasne dužine raspoređene u dva posebno osmišljena režima rada.',
                            'Režim 1. kombinuje crvenu, infracrvenu i duboku infracrvenu svetlost kako bi podržao stvaranje kolagena i delovao na fine linije i bore.',
                            'Režim ljubičaste kombinuje crvenu i plavu svetlost sa ciljem delovanja na bakterije povezane sa nastankom akni.',
                            'Njihovom primenom u okviru iste rutine moguće je tretirati celo lice, istovremeno usmeravajući negu na različite potrebe kože.',
                        ],
                    ],

                    // § 06 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Koje su prednosti korišćenja više boja LED svetlosti?',
                        'paragraphs' => [
                            'Kombinovanje različitih LED režima omogućava sveobuhvatniji i individualno prilagođen tretman kože.',
                            'Nije neophodno koristiti svaki režim svakog dana. Umesto toga, tretman možete prilagoditi onome što je vašoj koži potrebno u određenom trenutku.',
                            'Pogledajte ovaj članak koji detaljnije objašnjava koje se boje i talasne dužine koriste za delovanje na različite probleme i potrebe kože.',
                        ],
                    ],

                    // § 07 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Da li se crvena i zelena svetlost mogu koristiti zajedno?',
                        'paragraphs' => [
                            'Da! Crvena i zelena svetlosna terapija mogu se kombinovati, naročito kod osoba koje imaju problem sa hiperpigmentacijom i neujednačenim tenom.',
                            'Svaka talasna dužina prodire u različite delove dermisa. Zelena svetlost ima kraću talasnu dužinu i efikasno utiče na kontrolu proizvodnje melanina, pigmenta koji koži daje boju.',
                            'Crvena svetlost, sa druge strane, podstiče proizvodnju kolagena, čime doprinosi poboljšanju elastičnosti kože i obnovi kože oštećene usled hiperpigmentacije.',
                        ],
                    ],

                    // § 08 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Da li prvo treba koristiti crvenu ili plavu svetlost?',
                        'paragraphs' => [
                            'Iako još ne postoje konačni naučni dokazi koji potvrđuju da li je bolje prvo koristiti crvenu ili plavu svetlost, pojedini protokoli preporučuju da se tretman započne plavom svetlošću kraće talasne dužine od 465 nm, kako bi se delovalo na bakterije povezane sa nastankom akni, a zatim nastavi tretman crvenom svetlošću.',
                            'Takođe, bolje je koristiti plavu svetlost ujutru, jer ona kod nekih osoba može imati stimulativan efekat, pa njena večernja primena potencijalno može uticati na kvalitet i trajanje sna.',
                            'Crvena svetlost može se koristiti ujutru za poboljšanje raspoloženja ili uveče za podsticanje opuštanja pre spavanja.',
                        ],
                    ],

                    // § 09 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Da li je LED svetlosna terapija bezbedna?',
                        'paragraphs' => [
                            'Prema navodima iz studije 2018. godine, LED svetlosna terapija nema poznate negativne neželjene efekte. Uređaji za ovu vrstu terapije koriste sijalice koje ne emituju UV zračenje i ne proizvode toplotu, pa ne izazivaju oštećenja površine kože.',
                            'Studija objavljena 2018. godine u časopisu Journal of Clinical and Aesthetic Dermatology potvrđuje da fototerapija pomoću LED uređaja ima veoma dobar bezbednosni profil.',
                            'Ova vrsta terapije zahteva malo ili nimalo vremena za oporavak nakon tretmana. Ista studija potvrđuje i efikasnost fototerapijskih uređaja u tretiranju blagih do umerenih oblika akni, kao i u procesu zarastanja rana.',
                        ],
                        'source' => [
                            'text' => 'Phototherapy with Light Emitting Diodes: Treating a Broad Range of Medical and Aesthetic Conditions in Dermatology — PMC',
                            'href' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC6444701/',
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // BLOG 2
            // ══════════════════════════════════════════════════════════════
            [
                'slug'  => 'najbolja-boja-led-maske-za-tip-koze',
                'title' => 'Koja je najbolja boja LED maske za moj tip kože?',

                // Višeparagrafni uvod
                'excerpt' => implode("\n\n", [
                    'Odabir odgovarajuće boje LED svetlosti u skladu sa tipom kože može biti prilično zbunjujuć. Možda već znamo da terapija crvenom svetlošću ima značajne prednosti u anti-age nezi, dok se plava svetlost koristi za tretiranje i sprečavanje pojave akni. Međutim, kada je reč o ostalim bojama, stvari postaju manje jasne.',
                    'U ovom članku detaljnije ćemo objasniti mogućnosti uređaja sa 9 boja LED svetlosti, uz terapiju infracrvenom svetlošću, kako bismo vam pomogli da odaberete najbolju LED masku za konkretne potrebe i probleme vaše kože.',
                ]),

                'date'         => '2026-10-30',
                'reading_time' => 5,
                'is_published' => true,

                'sections' => [

                    // § 01 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Šta je LED svetlosna terapija?',
                        'paragraphs' => [
                            'LED svetlosna terapija je naučno istražena kozmetička tehnika koja koristi različite talasne dužine svetlosti kako bi pružila brojne prednosti za kožu. Ona može unaprediti vašu svakodnevnu rutinu nege i doprineti efikasnosti drugih kozmetičkih tretmana.',
                            'LED uređaji mogu sadržati do osam boja svetlosti, uz infracrvenu svetlost. Šta zapravo predstavljaju tih osam boja na LED maski? I odakle one potiču?',
                            'Sunce emituje tri kategorije svetlosti:',
                            [
                                'type'  => 'list',
                                'items' => [
                                    'Vidljiva svetlost',
                                    'Infracrvena svetlost',
                                    'Ultraljubičasta (UV) svetlost',
                                ],
                            ],
                            'LED sijalice u uređajima za svetlosnu terapiju mogu emitovati crvenu, plavu, žutu, narandžastu, zelenu, ljubičastu, cijan, belu ili infracrvenu svetlost.',
                            'Prvih osam boja pripada spektru vidljive svetlosti, što znači da ih ljudsko oko može videti. Infracrvena svetlost pripada nevidljivom delu spektra, zbog čega je ne možemo videti. Ipak, ona prodire dublje u kožu od ostalih navedenih vrsta svetlosti (o čemu će biti više reči u nastavku).',
                            'Spektar vidljive svetlosti obuhvata talasne dužine od 400 do 700 nm, dok se infracrveni spektar proteže od približno 700 do 1.100 nm.',
                        ],
                    ],

                    // § 02 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Koja boja LED svetlosti je najbolja za anti-age negu?',
                        'paragraphs' => [
                            'Terapija crvenom svetlošću (600–700 nm) smatra se jednim od najznačajnijih pristupa u anti-age nezi, jer prodire dublje u kožu od ostalih boja vidljive svetlosti.',
                            'Ona aktivira procese stvaranja kolagena i drugih važnih gradivnih elemenata kože, kao što su elastin i hijaluronska kiselina.',
                            'Terapija crvenom svetlošću takođe može doprineti procesima obnove DNK i poboljšanju funkcije mitohondrija.',
                            'Crvena svetlost je biološki aktivna kod ljudi, što znači da može uticati na funkciju ćelija. Ove promene u ćelijskoj aktivnosti povezane su sa njenim potencijalnim anti-age efektima, uključujući ublažavanje finih linija i poboljšanje elastičnosti kože tokom vremena.',
                            'Terapija infracrvenom svetlošću (700–1.100 nm) prodire dublje od svih ostalih boja u spektru vidljive svetlosti. Slično terapiji crvenom svetlošću, infracrvena svetlost ima potencijalne regenerativne i obnavljajuće efekte.',
                            'Studije su pokazale da ona može dopreti i izvan površinskih slojeva kože, delujući na tkiva, mišiće, pa čak i kosti.',
                            'Infracrvena svetlost podstiče ćelijsku aktivnost i stimuliše sintezu kolagena, što može doprineti povećanju elastičnosti i čvrstine kože, kao i ublažavanju pojave finih linija i bora.',
                            'Ukoliko je starenje kože vaša primarna briga, LED maska koja kombinuje crvenu i infracrvenu svetlost predstavlja odličan izbor.',
                        ],
                    ],

                    // § 03 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Koja boja LED svetlosti je najbolja za akne?',
                        'paragraphs' => [
                            'Plava LED svetlost smatra se veoma korisnom za kožu sklonu aknama. Ima antibakterijska i antimikrobna svojstva, što znači da može delovati na bakterije povezane sa nastankom akni na površini kože. Kada se primeni na kožu, plava svetlost podstiče stvaranje reaktivnih kiseoničnih jedinjenja koja uništavaju bakterije povezane sa aknama.',
                            'Plava svetlost takođe može umiriti upaljenu kožu, što je često povezano sa aknama, i doprineti ublažavanju vidljivosti ožiljaka od akni.',
                        ],
                    ],

                    // § 04 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Koja boja LED svetlosti je namenjena koži sklonoj rozacei i crvenilu?',
                        'paragraphs' => [
                            'Šta je rozacea? Rozacea je zapaljensko oboljenje kože koje se najčešće ispoljava kroz crvenilo, osip ili izbočine na licu, naročito na obrazima, nosu i bradi. Simptomi mogu trajati mesecima, pa čak i godinama.',
                            'Iako postoji malo dokaza koji ukazuju na to da LED svetlosna terapija može direktno da „leči" rozaceu, ona potencijalno može ublažiti pojedine simptome i poboljšati izgled kože. Preporučeni pristup je kombinacija crvene i infracrvene svetlosti sa plavom, zelenom ili žutom svetlošću.',
                            'Crvena i infracrvena svetlost prodiru dublje u kožu i podstiču procese obnove. Plava svetlost deluje na upalne procese, koji su jedno od glavnih obeležja rozacee, a može pomoći i u sprečavanju razvoja infekcija na koži.',
                            'Cijan svetlost predstavlja kombinaciju plave i zelene svetlosti (465 + 530 nm). Poznata je po umirujućem i regenerativnom delovanju na kožu, kao i po tome što može doprineti smanjenju vidljivosti popucalih kapilara.',
                        ],
                    ],

                    // § 05 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Koja boja LED svetlosti je najbolja za hiperpigmentaciju?',
                        'paragraphs' => [
                            'Najpre, šta uzrokuje hiperpigmentaciju? Hiperpigmentacija se prepoznaje po pojavi tamnih mrlja na koži. Primeri uključuju melazmu, staračke pege i postinflamatornu hiperpigmentaciju.',
                            'Neujednačen ten češće se javlja kod osoba sa tamnijim tonovima kože (najčešće III ili višim fototipom prema Fitzpatrick skali), ali može se javiti kod bilo kog tipa kože.',
                            'Uzroci uključuju dugotrajno izlaganje suncu, koje podstiče proizvodnju melanina, upalne procese, genetske faktore i određena zdravstvena stanja.',
                            'Terapija zelenom svetlošću (530 nm) razgrađuje melanin u koži i ometa sposobnost organizma da proizvodi dodatni melanin. Na taj način može smanjiti vidljivost hiperpigmentacije i doprineti svetlijem i ujednačenijem tenu.',
                        ],
                    ],

                    // § 06 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Dakle, koja je najbolja boja za LED masku?',
                        'paragraphs' => [
                            'Ne postoji jedna univerzalno najbolja LED maska, jer različite boje svetlosti imaju različite namene u zavisnosti od tipa kože i specifičnih potreba.',
                            'Uređaji koji kombinuju infracrvenu i crvenu svetlost popularan su izbor jer ove talasne dužine prodiru dublje u kožu i imaju značajan uticaj na ćelijsku aktivnost.',
                            'Mnogi uređaji sadrže crvenu svetlost u kombinaciji sa drugim bojama, kao što su plava, zelena i žuta. Plava svetlost je takođe veoma popularna zbog svog delovanja na bakterije povezane sa aknama, prvenstveno u epidermisu, odnosno površinskom sloju kože.',
                            'Crvena, plava i infracrvena svetlost takođe se povezuju sa FDA odobrenjima za određene uređaje, što može biti još jedan faktor koji potrošači uzimaju u obzir prilikom izbora. Više o tome kako FDA status može uticati na izbor LED maske možete pročitati u njihovom posebnom tekstu.',
                        ],
                    ],

                    // § 07 ─────────────────────────────────────────────────
                    [
                        'heading'    => 'Pregled: Kakvu ulogu ima sedam boja na LED maski?',
                        'paragraphs' => [],
                    ],

                    // § 08 ─────────────────────────────────────────────────
                    [
                        'heading' => 'Zaključak: Kako odabrati odgovarajuću LED masku?',
                        'paragraphs' => [
                            'Izbor idealne LED maske za lice može biti izazovan, a veliki broj dostupnih opcija može dodatno otežati odluku. Većini tipova kože može odgovarati kombinovani pristup LED svetlosnoj terapiji, posebno kada postoji više različitih potreba i problema sa kožom.',
                            'Kombinacija infracrvene i crvene svetlosti predstavlja snažan pristup anti-age nezi, dok se plava svetlost koristi za tretiranje blagih do umerenih akni.',
                            'Zelena svetlost inhibira proizvodnju melanina i usmerena je na ublažavanje znakova hiperpigmentacije. Za ublažavanje simptoma rozacee i crvenila, kombinacija crvene i infracrvene svetlosti sa zelenom, plavom ili cijan svetlošću, može dati dobre rezultate.',
                        ],
                    ],
                ],
            ],
        ];
    }
}
