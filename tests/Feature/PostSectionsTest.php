<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostSectionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::create([
            'name' => 'Admin', 'username' => 'admin', 'email' => 'a@a.rs', 'password' => 'secret123',
        ]));
    }

    private function save(array $sections)
    {
        return $this->postJson('/api/admin/posts', [
            'slug' => 'test', 'title' => 'Test', 'is_published' => true, 'sections' => $sections,
        ]);
    }

    public function test_subheadings_and_images_can_be_placed_anywhere_between_paragraphs(): void
    {
        $this->save([[
            'heading' => 'Sekcija',
            'paragraphs' => [
                'Uvod.',
                ['type' => 'image', 'url' => 'https://api.lumiskinproled.com/storage/media/a.jpg', 'alt' => 'A', 'caption' => 'Pre'],
                ['type' => 'subheading', 'text' => 'Podnaslov'],
                ['type' => 'paragraph', 'text' => 'Drugi paragraf.'],
                ['type' => 'image', 'url' => 'https://api.lumiskinproled.com/storage/media/b.jpg'],
                ['type' => 'list', 'items' => ['jedan', ' ', 'dva']],
            ],
        ]])->assertCreated();

        $this->assertSame([[
            'heading' => 'Sekcija',
            'paragraphs' => [
                'Uvod.',
                ['type' => 'image', 'url' => 'https://api.lumiskinproled.com/storage/media/a.jpg', 'alt' => 'A', 'caption' => 'Pre'],
                ['type' => 'subheading', 'text' => 'Podnaslov'],
                'Drugi paragraf.',
                ['type' => 'image', 'url' => 'https://api.lumiskinproled.com/storage/media/b.jpg'],
                ['type' => 'list', 'items' => ['jedan', 'dva']],
            ],
        ]], Post::first()->sections);

        // javni API vraća isti niz blokova
        $this->getJson('/api/posts/test')
            ->assertOk()
            ->assertJsonPath('sections.0.paragraphs.2.type', 'subheading')
            ->assertJsonPath('sections.0.paragraphs.4.type', 'image');
    }

    public function test_old_format_with_takeaways_section_image_and_source_still_works(): void
    {
        $this->save([
            ['type' => 'takeaways', 'heading' => 'Ključni zaključci', 'items' => ['A', 'B']],
            [
                'heading' => 'Stara',
                'image' => 'https://x.rs/storage/media/s.jpg',
                'source' => ['text' => 'Studija', 'href' => 'https://pmc.ncbi.nlm.nih.gov/1'],
                'paragraphs' => ['P1', ['type' => 'list', 'items' => ['x']]],
            ],
        ])->assertCreated();

        $sections = Post::first()->sections;
        $this->assertSame('takeaways', $sections[0]['type']);
        $this->assertSame('https://x.rs/storage/media/s.jpg', $sections[1]['image']);
        $this->assertSame('https://pmc.ncbi.nlm.nih.gov/1', $sections[1]['source']['href']);
        $this->assertSame(['P1', ['type' => 'list', 'items' => ['x']]], $sections[1]['paragraphs']);
    }

    public function test_drops_empty_and_unknown_blocks(): void
    {
        $this->save([[
            'heading' => 'S',
            'paragraphs' => [
                '   ',
                ['type' => 'subheading', 'text' => ''],
                ['type' => 'image', 'url' => ''],
                ['type' => 'html', 'html' => '<script>alert(1)</script>'],
                'Ostaje.',
            ],
        ]])->assertCreated();

        $this->assertSame(['Ostaje.'], Post::first()->sections[0]['paragraphs']);
    }

    public function test_rejects_dangerous_image_urls(): void
    {
        $this->save([[
            'heading' => 'S',
            'paragraphs' => [['type' => 'image', 'url' => 'javascript:alert(1)']],
        ]])->assertStatus(422)->assertJsonValidationErrors('sections.0.paragraphs.0.url');

        $this->assertSame(0, Post::count());
    }
}
