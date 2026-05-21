<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\WorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_document_can_be_submitted(): void
    {
        $user     = User::factory()->create(['is_active' => true]);
        $validator = User::factory()->create(['is_active' => true]);
        $category = DocumentCategory::factory()->create();

        $document = Document::factory()->create([
            'created_by'  => $user->id,
            'category_id' => $category->id,
            'status'      => 'draft',
        ]);

        $service = new WorkflowService();
        $this->actingAs($user);

        $service->submit($document, [[
            'name'    => 'Validation',
            'user_id' => $validator->id,
        ]]);

        $this->assertDatabaseHas('documents', [
            'id'     => $document->id,
            'status' => 'submitted',
        ]);

        $this->assertDatabaseHas('workflow_steps', [
            'document_id' => $document->id,
            'assigned_to' => $validator->id,
        ]);
    }

    public function test_workflow_step_can_be_approved(): void
    {
        $user     = User::factory()->create(['is_active' => true]);
        $validator = User::factory()->create(['is_active' => true]);
        $category = DocumentCategory::factory()->create();

        $document = Document::factory()->create([
            'created_by'  => $user->id,
            'category_id' => $category->id,
            'status'      => 'submitted',
        ]);

        $service = new WorkflowService();
        $this->actingAs($validator);

        $service->submit($document, [[
            'name'    => 'Validation',
            'user_id' => $validator->id,
        ]]);

        $step = $document->workflowSteps()->first();
        $service->approve($step, 'Approuvé');

        $document->refresh();
        $this->assertEquals('approved', $document->status);
    }
}