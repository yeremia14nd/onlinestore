<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\FlashSale;
use Illuminate\Support\Facades\DB;

class FlashSaleRaceConditionTest extends TestCase
{
    use RefreshDatabase;

    public function testRaceConditionDuringFlashSale()
    {
        // Create a product and a flash sale
        $product = Product::factory()->create();
        $flashSale = FlashSale::factory()->create([
            'product_id' => $product->id,
            'discount' => 50, // Your discount percentage
        ]);

        $this->assertInstanceOf(FlashSale::class, $flashSale);

        // Number of concurrent requests
        $concurrentRequests = 10;

        // Define an array to store the results
        $results = [];

        DB::beginTransaction(); // Start a database transaction

        try {
            // Create concurrent orders
            foreach (range(1, $concurrentRequests) as $index) {
                $response = $this->postJson('/api/orders', [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);

                $results[] = $response->status();
            }

            DB::commit(); // Commit the transaction

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction on any error
        }

        // Assertions
        $this->assertCount($concurrentRequests, $results);
        $this->assertEquals(0, $product->inventory);
    }
}
