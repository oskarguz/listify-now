<?php

use App\Models\Checklist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateChecklistTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_name(): void
    {
        $oldName = fake()->text(45);
        $newName = fake()->text(45);

        $checklist = Checklist::create(['name' => $oldName]);

        $this->assertDatabaseHas('checklists', ['name' => $oldName]);

        $this->postJson("/checklist/$checklist->id/update", ['name' => $newName])
            ->assertOk();

        $this->assertDatabaseHas('checklists', ['name' => $newName]);
        $this->assertDatabaseCount('checklists', 1);
    }
}
