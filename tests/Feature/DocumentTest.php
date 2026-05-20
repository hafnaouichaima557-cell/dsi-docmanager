<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected DocumentCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->user     = User::factory()->create();
        $this->category = DocumentCategory::factory()->create(['is_active' => true]);
    }

    // ─────────────────────────────────────────
    // Index — diag 1
    // ─────────────────────────────────────────

    /** @test */
    public function guest_cannot_access_documents_list(): void
    {
        $response = $this->get(route('documents.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_see_documents_list(): void
    {
        Document::factory()->count(3)->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertViewIs('documents.index');
        $response->assertViewHas('documents');
    }

    // ─────────────────────────────────────────
    // Create / Store — diag 1
    // ─────────────────────────────────────────

    /** @test */
    public function authenticated_user_can_see_create_form(): void
    {
        $response = $this->actingAs($this->user)
                         ->get(route('documents.create'));

        $response->assertStatus(200);
        $response->assertViewIs('documents.create');
    }

    /** @test */
    public function user_can_create_document_with_file(): void
    {
        $file = UploadedFile::fake()->create('rapport.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->user)->post(route('documents.store'), [
            'title'       => 'Rapport Q1',
            'description' => 'Rapport du premier trimestre',
            'category_id' => $this->category->id,
            'file'        => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'title'      => 'Rapport Q1',
            'created_by' => $this->user->id,
            'status'     => 'draft',
        ]);

        // Vérifier que la version a été créée
        $document = Document::where('title', 'Rapport Q1')->first();
        $this->assertDatabaseHas('document_versions', [
            'document_id'    => $document->id,
            'version_number' => 1,
            'is_current'     => true,
        ]);
    }

    /** @test */
    public function document_creation_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->user)
                         ->post(route('documents.store'), []);

        $response->assertSessionHasErrors(['title', 'category_id', 'file']);
    }

    /** @test */
    public function document_creation_fails_with_file_exceeding_10mb(): void
    {
        $bigFile = UploadedFile::fake()->create('big.pdf', 11000, 'application/pdf');

        $response = $this->actingAs($this->user)->post(route('documents.store'), [
            'title'       => 'Gros fichier',
            'category_id' => $this->category->id,
            'file'        => $bigFile,
        ]);

        $response->assertSessionHasErrors(['file']);
    }

    /** @test */
    public function document_reference_is_auto_generated(): void
    {
        $file = UploadedFile::fake()->create('doc.pdf', 500);

        $this->actingAs($this->user)->post(route('documents.store'), [
            'title'       => 'Test Référence',
            'category_id' => $this->category->id,
            'file'        => $file,
        ]);

        $document = Document::where('title', 'Test Référence')->first();
        $this->assertNotNull($document->reference);
        $this->assertStringStartsWith('DOC-', $document->reference);
    }

    // ─────────────────────────────────────────
    // Show
    // ─────────────────────────────────────────

    /** @test */
    public function user_can_view_document_details(): void
    {
        $document = Document::factory()->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('documents.show', $document));

        $response->assertStatus(200);
        $response->assertViewIs('documents.show');
        $response->assertViewHas('document');
    }

    // ─────────────────────────────────────────
    // Edit / Update — diag 2
    // ─────────────────────────────────────────

    /** @test */
    public function document_owner_can_access_edit_form(): void
    {
        $document = Document::factory()->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
            'status'      => 'draft',
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('documents.edit', $document));

        $response->assertStatus(200);
        $response->assertViewIs('documents.edit');
    }

    /** @test */
    public function document_owner_can_update_document(): void
    {
        $document = Document::factory()->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
            'status'      => 'draft',
        ]);

        $response = $this->actingAs($this->user)->patch(route('documents.update', $document), [
            'title'       => 'Titre modifié',
            'category_id' => $this->category->id,
        ]);

        $response->assertRedirect(route('documents.show', $document));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'id'     => $document->id,
            'title'  => 'Titre modifié',
            'status' => 'under_review',
        ]);
    }

    /** @test */
    public function updating_with_new_file_creates_new_version(): void
    {
        $document = Document::factory()->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
            'status'      => 'draft',
        ]);

        // Version initiale
        $document->versions()->create([
            'version_number' => 1,
            'file_path'      => 'documents/v1.pdf',
            'file_name'      => 'v1.pdf',
            'file_type'      => 'pdf',
            'file_size'      => 1024,
            'checksum'       => 'abc123',
            'uploaded_by'    => $this->user->id,
            'is_current'     => true,
        ]);

        $newFile = UploadedFile::fake()->create('v2.pdf', 500);

        $this->actingAs($this->user)->patch(route('documents.update', $document), [
            'title'       => $document->title,
            'category_id' => $this->category->id,
            'file'        => $newFile,
        ]);

        // Nouvelle version créée
        $this->assertEquals(2, $document->fresh()->versions()->count());

        // La nouvelle version est la courante
        $this->assertDatabaseHas('document_versions', [
            'document_id'    => $document->id,
            'version_number' => 2,
            'is_current'     => true,
        ]);

        // L'ancienne version n'est plus courante
        $this->assertDatabaseHas('document_versions', [
            'document_id'    => $document->id,
            'version_number' => 1,
            'is_current'     => false,
        ]);
    }

    /** @test */
    public function unauthorized_user_cannot_update_document(): void
    {
        $owner    = User::factory()->create();
        $other    = User::factory()->create();
        $document = Document::factory()->create([
            'created_by'  => $owner->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($other)->patch(route('documents.update', $document), [
            'title'       => 'Tentative de modification',
            'category_id' => $this->category->id,
        ]);

        $response->assertForbidden();
    }

    // ─────────────────────────────────────────
    // Disable — diag 3
    // ─────────────────────────────────────────

    /** @test */
    public function authorized_user_can_disable_document(): void
    {
        $document = Document::factory()->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
            'status'      => 'published',
        ]);

        $response = $this->actingAs($this->user)
                         ->patch(route('documents.disable', $document));

        $response->assertRedirect(route('documents.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'id'          => $document->id,
            'status'      => 'disabled',
            'disabled_by' => $this->user->id,
        ]);

        $this->assertNotNull($document->fresh()->disabled_at);
    }

    /** @test */
    public function unauthorized_user_cannot_disable_document(): void
    {
        $owner    = User::factory()->create();
        $other    = User::factory()->create();
        $document = Document::factory()->create([
            'created_by'  => $owner->id,
            'category_id' => $this->category->id,
            'status'      => 'published',
        ]);

        $response = $this->actingAs($other)
                         ->patch(route('documents.disable', $document));

        $response->assertForbidden();
    }

    // ─────────────────────────────────────────
    // Publish — diag 5
    // ─────────────────────────────────────────

    /** @test */
    public function authorized_user_can_publish_approved_document(): void
    {
        $document = Document::factory()->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
            'status'      => 'approved',
        ]);

        $response = $this->actingAs($this->user)
                         ->patch(route('documents.publish', $document));

        $response->assertRedirect(route('documents.show', $document));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'id'     => $document->id,
            'status' => 'published',
        ]);

        $this->assertNotNull($document->fresh()->published_at);
    }

    /** @test */
    public function unauthorized_user_cannot_publish_document(): void
    {
        $owner    = User::factory()->create();
        $other    = User::factory()->create();
        $document = Document::factory()->create([
            'created_by'  => $owner->id,
            'category_id' => $this->category->id,
            'status'      => 'approved',
        ]);

        $response = $this->actingAs($other)
                         ->patch(route('documents.publish', $document));

        $response->assertForbidden();
    }

    // ─────────────────────────────────────────
    // Delete
    // ─────────────────────────────────────────

    /** @test */
    public function owner_can_delete_document(): void
    {
        $document = Document::factory()->create([
            'created_by'  => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
                         ->delete(route('documents.destroy', $document));

        $response->assertRedirect(route('documents.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('documents', ['id' => $document->id]);
    }

    /** @test */
    public function unauthorized_user_cannot_delete_document(): void
    {
        $owner    = User::factory()->create();
        $other    = User::factory()->create();
        $document = Document::factory()->create([
            'created_by'  => $owner->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($other)
                         ->delete(route('documents.destroy', $document));

        $response->assertForbidden();
        $this->assertDatabaseHas('documents', ['id' => $document->id, 'deleted_at' => null]);
    }

    // ─────────────────────────────────────────
    // Model helpers
    // ─────────────────────────────────────────

    /** @test */
    public function document_helpers_return_correct_status(): void
    {
        $draft     = Document::factory()->make(['status' => 'draft']);
        $published = Document::factory()->make(['status' => 'published']);
        $disabled  = Document::factory()->make(['status' => 'disabled']);
        $approved  = Document::factory()->make(['status' => 'approved']);

        $this->assertTrue($draft->isDraft());
        $this->assertTrue($published->isPublished());
        $this->assertTrue($disabled->isDisabled());
        $this->assertTrue($approved->canBePublished());
        $this->assertTrue($draft->canBeSubmitted());
    }
}