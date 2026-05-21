<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['is_active' => true]);
    }

    public function test_documents_page_requires_auth(): void
    {
        $response = $this->get('/documents');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_documents(): void
    {
        $response = $this->actingAs($this->user)->get('/documents');
        $response->assertStatus(200);
    }

    public function test_user_can_create_document(): void
    {
        Storage::fake('local');

        $category = DocumentCategory::factory()->create();
        $file     = UploadedFile::fake()->create('test.pdf', 100);

        $response = $this->actingAs($this->user)->post('/documents', [
            'title'       => 'Document Test',
            'description' => 'Description test',
            'category_id' => $category->id,
            'priority'    => 'normal',
            'file'        => $file,
        ]);

        $this->assertDatabaseHas('documents', [
            'title'      => 'Document Test',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_document_has_auto_reference(): void
    {
        Storage::fake('local');

        $category = DocumentCategory::factory()->create();
        $file     = UploadedFile::fake()->create('test.pdf', 100);

        $this->actingAs($this->user)->post('/documents', [
            'title'       => 'Document Référence',
            'category_id' => $category->id,
            'priority'    => 'normal',
            'file'        => $file,
        ]);

        $doc = Document::where('title', 'Document Référence')->first();
        $this->assertNotNull($doc->reference);
        $this->assertStringStartsWith('DOC-', $doc->reference);
    }
}