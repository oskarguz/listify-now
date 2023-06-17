<?php

namespace Checklist;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_has_access(): void
    {
        $owner = User::factory()->create();

        /** @var Checklist $checklist */
        $checklist = Checklist::factory()
            ->privateVisibility()
            ->for($owner, 'createdBy')
            ->has(ChecklistItem::factory()->count(2), 'items')
            ->create();

        \Auth::login($owner);

        // view
        $this->get("/checklist/$checklist->id")
            ->assertOk();
        // update
        $this->postJson("/checklist/$checklist->id/update", ['name' => \Str::random()])
            ->assertOk();

        $item = $checklist->items->first();

        // update item
        $this->postJson("/checklist/$checklist->id/items/$item->id/update", [
            'description' => \Str::random()
        ])->assertOk();

        // delete item
        $this->postJson("/checklist/$checklist->id/items/$item->id/destroy")
            ->assertOk();

        //create item
        $this->postJson("/checklist/$checklist->id/items/create", ChecklistItem::factory()->definition())
            ->assertOk();

        // delete
        $this->postJson("/checklist/$checklist->id/destroy")
            ->assertOk();

        $this->assertDatabaseCount('checklists', 0);
        $this->assertDatabaseCount('checklist_items', 0);
    }

    public function test_other_user_has_not_access(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        /** @var Checklist $checklist */
        $checklist = Checklist::factory()
            ->privateVisibility()
            ->for($owner, 'createdBy')
            ->has(ChecklistItem::factory()->count(2), 'items')
            ->create();

        \Auth::login($otherUser);

        // view
        $this->get("/checklist/$checklist->id")
            ->assertForbidden();
        // update
        $this->postJson("/checklist/$checklist->id/update", ['name' => \Str::random()])
            ->assertForbidden();

        $item = $checklist->items->first();

        // update item
        $this->postJson("/checklist/$checklist->id/items/$item->id/update", [
            'description' => \Str::random()
        ])->assertForbidden();

        // delete item
        $this->postJson("/checklist/$checklist->id/items/$item->id/destroy")
            ->assertForbidden();

        //create item
        $this->postJson("/checklist/$checklist->id/items/create", ChecklistItem::factory()->definition())
            ->assertForbidden();

        // delete
        $this->postJson("/checklist/$checklist->id/destroy")
            ->assertForbidden();

        $this->assertDatabaseCount('checklists', 1);
        $this->assertDatabaseCount('checklist_items', 2);
    }
}
