<?php

namespace Tests\Feature;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecklistPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create(): void
    {
        $this->get('/checklist/create')
            ->assertOk();

        $this->postJson('/checklist/create', Checklist::factory()->definition())
            ->assertOk();
    }

    public function test_can_update(): void
    {
        $checklist = Checklist::factory()->create();

        $this->postJson("/checklist/$checklist->id/update", Checklist::factory()->definition())
            ->assertOk();

        $user = User::factory()->create();
        $checklist = Checklist::factory()
            ->for($user, 'createdBy')
            ->create();

        \Auth::login($user);

        $this->postJson("/checklist/$checklist->id/update", Checklist::factory()->definition())
            ->assertOk();

        \Auth::logout();

        $this->postJson("/checklist/$checklist->id/update", Checklist::factory()->definition())
            ->assertForbidden();
    }

    public function test_can_view(): void
    {
        $checklist = Checklist::factory()->create();

        $this->get("/checklist/$checklist->id")->assertOk();

        $user = User::factory()->create();
        $checklist = Checklist::factory()
            ->for($user, 'createdBy')
            ->create();

        \Auth::login($user);

        $this->get("/checklist/$checklist->id")
            ->assertOk();

        \Auth::logout();

        $this->get("/checklist/$checklist->id")
            ->assertForbidden();
    }

    public function test_can_delete(): void
    {
        $this->markTestIncomplete();
        // @TODO not implemented yet
    }

    public function test_can_create_item(): void
    {
        $checklist = Checklist::factory()->create();

        $this->postJson("/checklist/$checklist->id/items/create", ChecklistItem::factory()->definition())
            ->assertOk();

        $user = User::factory()->create();

        $checklist = Checklist::factory()
            ->for($user, 'createdBy')
            ->create();

        \Auth::login($user);

        $this->postJson("/checklist/$checklist->id/items/create", ChecklistItem::factory()->definition())
            ->assertOk();

        \Auth::logout();

        $this->postJson("/checklist/$checklist->id/items/create", ChecklistItem::factory()->definition())
            ->assertForbidden();
    }

    public function test_can_update_item(): void
    {
        $checklist = Checklist::factory()
            ->has(ChecklistItem::factory()->count(1), 'items')
            ->create();
        $item = $checklist->items()->first();

        $this->postJson(
            "/checklist/$checklist->id/items/$item->id/update",
            ChecklistItem::factory()->definition()
        )->assertOk();

        $user = User::factory()->create();
        $checklist = Checklist::factory()
            ->has(ChecklistItem::factory()->count(1), 'items')
            ->for($user, 'createdBy')
            ->create();
        $item = $checklist->items()->first();

        \Auth::login($user);

        $this->postJson(
            "/checklist/$checklist->id/items/$item->id/update",
            ChecklistItem::factory()->definition()
        )->assertOk();

        \Auth::logout();

        $this->postJson(
            "/checklist/$checklist->id/items/$item->id/update",
            ChecklistItem::factory()->definition()
        )->assertForbidden();
    }

    public function test_can_delete_item(): void
    {
        $checklist = Checklist::factory()
            ->has(ChecklistItem::factory()->count(1), 'items')
            ->create();
        $item = $checklist->items()->first();

        $this->postJson(
            "/checklist/$checklist->id/items/$item->id/destroy",
            ChecklistItem::factory()->definition()
        )->assertOk();

        $user = User::factory()->create();
        $checklist = Checklist::factory()
            ->has(ChecklistItem::factory()->count(2), 'items')
            ->for($user, 'createdBy')
            ->create();
        $item = $checklist->items()->first();

        \Auth::login($user);

        $this->postJson(
            "/checklist/$checklist->id/items/$item->id/destroy",
            ChecklistItem::factory()->definition()
        )->assertOk();

        \Auth::logout();

        $item = $checklist->items()->first();

        $this->postJson(
            "/checklist/$checklist->id/items/$item->id/destroy",
            ChecklistItem::factory()->definition()
        )->assertForbidden();
    }
}
