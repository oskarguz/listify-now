<?php

namespace Checklist;

use App\Enum\Visibility;
use App\Models\Checklist;
use App\Models\User;
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

    public function test_cannot_update_visibility_for_anonymous_checklist(): void
    {
        $checklist = Checklist::factory()->create(); // default: Public

        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Private->value])
            ->assertUnprocessable();

        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Public->value])
            ->assertOk();

        $randomUser = User::factory()->create();
        \Auth::login($randomUser);

        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Private->value])
            ->assertUnprocessable();

        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Public->value])
            ->assertOk();

        $this->assertDatabaseHas('checklists', ['visibility' => $checklist->visibility->value]);
        $this->assertDatabaseCount('checklists', 1);
    }

    public function test_only_owner_can_change_visibility(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $checklist = Checklist::factory()
            ->for($owner, 'createdBy')
            ->create();

        // anonymous user
        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Private->value])
            ->assertUnprocessable();

        $this->assertDatabaseHas('checklists', ['visibility' => $checklist->visibility->value]);

        // owner
        \Auth::login($owner);

        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Private->value])
            ->assertOk();

        $this->assertDatabaseHas('checklists', ['visibility' => Visibility::Private->value]);

        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Public->value])
            ->assertOk();

        \Auth::logout();
        // random user
        \Auth::login($otherUser);

        $checklist->refresh();

        $this->postJson("/checklist/$checklist->id/update", ['visibility' => Visibility::Private->value])
            ->assertUnprocessable();

        $this->assertDatabaseHas('checklists', ['visibility' => $checklist->visibility->value]);
    }
}
