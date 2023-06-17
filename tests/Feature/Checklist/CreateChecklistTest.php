<?php


namespace Checklist;

use App\Enum\Visibility;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class CreateChecklistTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_without_items(): void
    {
        $definition = Checklist::factory()->definition();
        $response = $this->postJson('/checklist/create', $definition);

        $response->assertOk();
        $response->assertJsonStructure([
            'id', 'name', 'items', 'visibility'
        ]);

        $this->assertDatabaseHas('checklists', [
            'name' => $definition['name'],
            'created_by_id' => null,
            'visibility' => $definition['visibility'],
        ]);
        $this->assertDatabaseCount('checklists', 1);

        $privateVisibility = Checklist::factory()->definition();
        $privateVisibility['visibility'] = Visibility::Private->value;

        $this->postJson('/checklist/create', $privateVisibility)
            ->assertUnprocessable();

        $this->assertDatabaseCount('checklists', 1);
    }

    public function test_create_with_single_item(): void
    {
        $response = $this->postJson('/checklist/create', [
            'name' => 'lorem ipsum',
            'items' => [
                [
                    'description' => 'test description',
                    'checked' => false,
                ]
            ]
        ]);

        $response->assertOk();

        $items = $response->json('items');
        $this->assertIsArray($items);
        $this->assertCount(1, $items);

        $item = $items[0];
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('description', $item);
        $this->assertArrayHasKey('checked', $item);

        $this->assertDatabaseHas('checklists', [
            'name' => 'lorem ipsum',
            'created_by_id' => null,
        ]);
        $this->assertDatabaseCount('checklists', 1);
        $this->assertDatabaseHas('checklist_items', [
            'description' => 'test description',
            'created_by_id' => null
        ]);
        $this->assertDatabaseCount('checklist_items', 1);
    }

    public function test_create_with_many_items(): void
    {
        $response = $this->postJson('/checklist/create', [
            'name' => 'lorem ipsum',
            'items' => [
                [
                    'description' => 'test description',
                    'checked' => false,
                ],
                [
                    'description' => 'test description - 2',
                    'checked' => true,
                ]
            ]
        ]);

        $response->assertOk();

        $items = $response->json('items');
        $this->assertIsArray($items);
        $this->assertCount(2, $items);

        $this->assertDatabaseHas('checklist_items', ['description' => 'test description', 'checked' => false]);
        $this->assertDatabaseHas('checklist_items', ['description' => 'test description - 2', 'checked' => true]);
        $this->assertDatabaseCount('checklist_items', 2);
    }

    public function test_empty_name_validation(): void
    {
        $response = $this->postJson('/checklist/create', [
            'name' => ''
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure(['message', 'errors']);
    }

    public function test_name_too_long_validation(): void
    {
        $this->postJson('/checklist/create', [
            'name' => Str::random(400),
        ])->assertOk();

        $this->postJson('/checklist/create', [
            'name' => Str::random(401),
        ])->assertUnprocessable();
    }

    public function test_create_as_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseCount('users', 1);

        Auth::login($user);

        $response = $this->postJson('/checklist/create', Checklist::factory()->definition());

        $response->assertOk();
        $data = $response->json();

        $this->assertArrayHasKey('created_by', $data);
        $this->assertIsArray($data['created_by']);

        $this->assertDatabaseCount('checklists', 1);
        $this->assertDatabaseHas('checklists', [
            'created_by_id' => $user->id,
            'visibility' => Visibility::Public->value
        ]);

        // create private list
        $privateChecklist = Checklist::factory()->definition();
        $privateChecklist['visibility'] = Visibility::Private->value;

        $response = $this->postJson('/checklist/create', $privateChecklist);
        $response->assertOk();

        $data = $response->json();

        $this->assertDatabaseHas('checklists', [
            'id' => $data['id'],
            'visibility' => $data['visibility']
        ]);
    }

    public function test_create_with_items_as_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->assertDatabaseCount('users', 1);

        Auth::login($user);

        $response = $this->postJson('/checklist/create', [
            'name' => 'lorem ipsum',
            'items' => [
                [
                    'description' => Str::random(25),
                    'checked' => false,
                ]
            ]
        ]);

        $response->assertOk();
        $data = $response->json();

        $this->assertIsArray($data['items']);
        $this->assertCount(1, $data['items']);
        $this->assertArrayHasKey('id', $data['items'][0]);

        $this->assertDatabaseCount('checklist_items', 1);
        $this->assertDatabaseHas('checklist_items', [
            'created_by_id' => $user->id,
        ]);
    }

    public function test_cannot_create_for_other_user(): void
    {
        $loggedUser = User::factory()->create();

        Auth::login($loggedUser);

        $otherUser = User::factory()->create();

        $checklist = Checklist::factory()->definition();
        $checklist['created_by_id'] = $otherUser->id;

        $item = ChecklistItem::factory()->definition();
        $item['created_by_id'] = $otherUser->id;

        $checklist['items'] = [$item];
        $this->postJson('/checklist/create', $checklist)->assertOk();

        $this->assertDatabaseCount('checklists', 1);
        $this->assertDatabaseHas('checklists', [
            'name' => $checklist['name'],
            'created_by_id' => $loggedUser->id,
        ]);

        $this->assertDatabaseCount('checklist_items', 1);
        $this->assertDatabaseHas('checklist_items', [
            'description' => $item['description'],
            'created_by_id' => $loggedUser->id,
        ]);
    }
}
