<?php
namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CustomerTest extends TestCase
{

    public function testCreateCustomer()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $response = $this->postJson('/api/customers', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 30,
            'dob' => '1990-01-01',
            'email' => 'john.doe@example.com'
        ]);


        $response->assertStatus(201);
    }

    public function testListCustomers()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200);
    }

    public function testShowCustomer()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $customer = Customer::factory()->create();

        $response = $this->getJson('/api/customers/' . $customer->id);

        $response->assertStatus(200)
            ->assertJson($customer->toArray());
    }

    public function testUpdateCustomer()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $customer = Customer::factory()->create();

        $response = $this->putJson('/api/customers/' . $customer->id, [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'age' => 25,
            'dob' => '1995-01-01',
            'email' => 'jane.doe@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $customer->id,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'age' => 25,
                'dob' => '1995-01-01',
                'email' => 'jane.doe@example.com'
            ]);
    }

    public function testDeleteCustomer()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $customer = Customer::factory()->create();

        $response = $this->deleteJson('/api/customers/' . $customer->id);

        $response->assertStatus(204);
    }
}
