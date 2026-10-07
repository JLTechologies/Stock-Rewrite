<?php

namespace Tests\Feature;

use App\Enums\KbFormat;
use App\Filament\App\Pages\KnowledgeBase;
use App\Filament\Resources\KbArticles\Pages\CreateKbArticle;
use App\Models\KbArticle;
use App\Models\KbCategory;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class KnowledgeBaseTest extends TestCase
{
    use RefreshDatabase;

    protected function editor(): User
    {
        return User::factory()->withPermissions(['knowledge_base' => ['create', 'update', 'delete']])->inTeam()->create();
    }

    #[Test]
    public function every_employee_reads_published_articles_in_their_language(): void
    {
        $article = KbArticle::factory()->create([
            'title' => ['nl' => 'Verlof aanvragen', 'fr' => 'Demander un congé', 'en' => 'Request leave'],
            'body' => ['nl' => 'Klik op **Aanvragen**.', 'fr' => 'Cliquez sur **Demander**.', 'en' => 'Click **Request**.'],
        ]);
        $guest = User::factory()->create(['locale' => 'fr']); // no team, no permissions

        $this->actingAs($guest)->get('/knowledge-base')->assertOk()->assertSee('Demander un congé');
        $this->actingAs($guest)->get('/knowledge-base?article='.$article->id)->assertOk()->assertSee('<strong>Demander</strong>', escape: false);
        $this->actingAs($guest)->get('/knowledge-base-articles')->assertForbidden();
    }

    #[Test]
    public function drafts_and_hidden_categories_are_only_shown_to_editors(): void
    {
        $draft = KbArticle::factory()->draft()->create(['title' => ['nl' => 'Concepttekst']]);
        $hidden = KbArticle::factory()->for(KbCategory::factory()->hidden(), 'category')->create(['title' => ['nl' => 'Geheime procedure']]);
        $employee = User::factory()->inTeam()->create();

        $this->actingAs($employee)->get('/knowledge-base')->assertOk()->assertDontSee('Concepttekst')->assertDontSee('Geheime procedure');
        $this->actingAs($employee)->get('/knowledge-base?article='.$draft->id)->assertNotFound();
        $this->actingAs($employee)->get('/knowledge-base?article='.$hidden->id)->assertNotFound();

        $this->actingAs($this->editor())->get('/knowledge-base')->assertOk()->assertSee('Concepttekst')->assertSee('Geheime procedure');
    }

    #[Test]
    public function search_looks_in_title_and_text(): void
    {
        KbArticle::factory()->create(['title' => ['nl' => 'Wagen tanken'], 'body' => ['nl' => 'Gebruik de **tankkaart** van de firma.']]);
        KbArticle::factory()->create(['title' => ['nl' => 'Verlof']]);
        $this->actingAs(User::factory()->inTeam()->create());
        Filament::setCurrentPanel('app');

        Livewire::test(KnowledgeBase::class)
            ->set('search', 'tankkaart')
            ->assertSee('Wagen tanken')
            ->assertDontSee('Verlof');
    }

    #[Test]
    public function editors_write_articles_with_downloads_that_only_signed_in_users_can_open(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $category = KbCategory::factory()->create();
        $editor = $this->editor();
        $this->actingAs($editor);
        Filament::setCurrentPanel('app');

        Livewire::test(CreateKbArticle::class)
            ->fillForm([
                'kb_category_id' => $category->id,
                'title' => ['nl' => 'Werfreglement', 'fr' => 'Règlement de chantier', 'en' => 'Site rules'],
                'format' => KbFormat::Markdown->value,
                'body' => ['nl' => 'Zie de bijlage.'],
                'attachments' => [UploadedFile::fake()->create('reglement.pdf', 50, 'application/pdf')],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = KbArticle::sole();
        $this->assertTrue($article->editor->is($editor));
        $this->assertSame('reglement.pdf', $article->downloads()[0]['name']);

        $url = route('kb.download', ['article' => $article, 'index' => 0]);
        auth()->logout();
        $this->get($url)->assertRedirect();
        $this->actingAs(User::factory()->create())->get($url)->assertOk()->assertDownload('reglement.pdf');
    }

    #[Test]
    public function the_module_can_be_switched_off(): void
    {
        KbArticle::factory()->create();
        $this->setModules(['knowledge_base' => false]);
        $editor = $this->editor();

        $this->actingAs($editor)->get('/knowledge-base')->assertForbidden();
        $this->actingAs($editor)->get('/knowledge-base-articles')->assertForbidden();
        $this->actingAs($editor)->get(route('kb.download', ['article' => KbArticle::sole(), 'index' => 0]))->assertNotFound();
    }
}
