<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user, 'sanctum');
    }

    public function can_create_item()
    {
        $this->authenticate();

        $response = $this->postJson('/api/items', [
            'name' => 'Item One',
            'description' => 'Test description',
            'code' => 'ITEM001',
            'status' => 'active',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Item One',
                     'code' => 'ITEM001',
                 ]);

        $this->assertDatabaseHas('items', [
            'code' => 'ITEM001',
        ]);
    }

    public function can_filter_items()
    {
        $this->authenticate();

        Item::create([
            'name' => 'Active Item',
            'description' => 'Active desc',
            'code' => 'ACTIVE001',
            'status' => 'active',
        ]);

        Item::create([
            'name' => 'Inactive Item',
            'description' => 'Inactive desc',
            'code' => 'INACTIVE001',
            'status' => 'inactive',
        ]);

        $response = $this->getJson('/api/items?status=active');

        $response->assertStatus(200)
                 ->assertJsonFragment(['code' => 'ACTIVE001'])
                 ->assertJsonMissing(['code' => 'INACTIVE001']);
    }


    public function can_update_item()
    {
        $this->authenticate();

        $item = Item::create([
            'name' => 'Old Name',
            'description' => 'Old desc',
            'code' => 'UPD001',
            'status' => 'active',
        ]);

        $response = $this->putJson("/api/items/{$item->uuid}", [
            'name' => 'New Name',
            'description' => 'Updated desc',
            'code' => 'UPD001',
            'status' => 'inactive',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'New Name',
                     'status' => 'inactive',
                 ]);

        $this->assertDatabaseHas('items', [
            'uuid' => $item->uuid,
            'status' => 'inactive',
        ]);
    }


    public function unauthenticated_users_cannot_access_items()
    {
        $this->getJson('/api/items')->assertStatus(401);
    }
}