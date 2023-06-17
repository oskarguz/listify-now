<?php


namespace Checklist;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class ChecklistItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_item(): void
    {
        $checklist = Checklist::create(['name' => Str::random(40)]);

        $this->assertDatabaseHas('checklists', ['name' => $checklist->name]);

        $response = $this->postJson("/checklist/$checklist->id/items/create", ['description' => Str::random(20)]);
        $response->assertOk();

        $item = $response->json();

        $this->assertDatabaseCount('checklist_items', 1);
        $this->assertDatabaseHas('checklist_items', ['description' => $item['description']]);
    }

    public function test_create_item_as_authenticated_user(): void
    {
        $user = User::factory()->create();
        $checklist = Checklist::factory()
            ->for($user, 'createdBy')
            ->create();

        Auth::login($user);

        $response = $this->postJson("/checklist/$checklist->id/items/create", ChecklistItem::factory()->definition());
        $response->assertOk();

        $item = $response->json();

        $this->assertDatabaseCount('checklist_items', 1);
        $this->assertDatabaseHas('checklist_items', [
            'description' => $item['description'],
            'created_by_id' => $user->id,
        ]);
        $this->assertSame($user->id, $item['created_by_id']);
    }

    public function test_update_item(): void
    {
        $oldDescription = Str::random(30);
        $newDescription = Str::random(25);

        $checklist = Checklist::create(['name' => Str::random(40)]);
        $checklist->items()->create([
            'description' => $oldDescription,
            'checked' => true
        ]);

        $this->assertDatabaseHas('checklist_items', [
            'description' => $oldDescription,
            'checked' => true
        ]);

        $item = $checklist->items()->first();

        $response = $this->postJson("/checklist/$checklist->id/items/$item->id/update", [
            'description' => $newDescription,
            'checked' => false
        ]);
        $response->assertOk();

        $this->assertDatabaseCount('checklist_items', 1);
        $this->assertDatabaseHas('checklist_items', [
            'description' => $newDescription,
            'checked' => false
        ]);
    }

    public function test_update_only_single_field(): void
    {
        $oldChecked = true;
        $newChecked = false;

        $checklist = Checklist::create(['name' => Str::random(40)]);
        $checklist->items()->create([
            'description' => Str::random(),
            'checked' => $oldChecked
        ]);

        $this->assertDatabaseHas('checklist_items', [
            'checked' => $oldChecked
        ]);

        $item = $checklist->items()->first();

        $response = $this->postJson("/checklist/$checklist->id/items/$item->id/update", [
            'checked' => $newChecked
        ]);
        $response->assertOk();

        $this->assertDatabaseCount('checklist_items', 1);
        $this->assertDatabaseHas('checklist_items', [
            'checked' => $newChecked
        ]);
    }

    public function test_item_validation(): void
    {
        $checklist = Checklist::create(['name' => Str::random(40)]);
        $url = "/checklist/$checklist->id/items/create";

        $this->postJson($url, [])->assertUnprocessable();
        $this->postJson($url, ['description' => ''])->assertUnprocessable();
        $this->postJson($url, ['description' => 'test', 'checked' => 100])->assertUnprocessable();
        $this->postJson($url, ['description' => Str::random(401)])->assertUnprocessable();
    }

    public function test_delete_item(): void
    {
        $checklist = Checklist::create(['name' => Str::random(40)]);
        $checklist->items()->create([
            'description' => Str::random(10),
            'checked' => true
        ]);
        $item = $checklist->items()->first();

        $this->assertDatabaseCount('checklist_items', 1);

        $this->postJson("/checklist/$checklist->id/items/$item->id/destroy")
            ->assertOk();

        $this->assertDatabaseCount('checklist_items', 0);
        $this->assertDatabaseCount('checklists', 1);
    }
}
