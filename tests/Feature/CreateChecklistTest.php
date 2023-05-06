<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateChecklistTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_without_items(): void
    {
        $response = $this->postJson('/checklist/create', [
            'name' => 'lorem ipsum'
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'id', 'name', 'items'
        ]);

        $this->assertDatabaseHas('checklists', ['name' => 'lorem ipsum']);
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

        $this->assertDatabaseHas('checklists', ['name' => 'lorem ipsum']);
        $this->assertDatabaseCount('checklists', 1);
        $this->assertDatabaseHas('checklist_items', ['description' => 'test description']);
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
}
