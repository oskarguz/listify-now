<?php

namespace Tests\Feature;

use App\Models\Checklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecklistPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create(): void
    {
        $this->get('/checklist/create')->assertOk();
        $this->postJson('/checklist/create', ['name' => \Str::random(5)])->assertOk();
    }

    public function test_can_update(): void
    {
        $checklist = Checklist::factory()->create();

        $this->postJson("/checklist/$checklist->id/update", ['name' => \Str::random(15)])->assertOk();

        $user = User::factory()->create();
        $checklist = Checklist::factory()
            ->for($user, 'createdBy')
            ->create();

        \Auth::login($user);

        $this->postJson("/checklist/$checklist->id/update", ['name' => \Str::random(15)])->assertOk();

        \Auth::logout();

        $this->postJson("/checklist/$checklist->id/update", ['name' => \Str::random(15)])->assertForbidden();
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

        $this->get("/checklist/$checklist->id")->assertOk();

        \Auth::logout();

        $this->get("/checklist/$checklist->id")->assertForbidden();
    }

    public function test_can_delete(): void
    {
        $this->markTestIncomplete();
        // @TODO not implemented yet
    }
}
