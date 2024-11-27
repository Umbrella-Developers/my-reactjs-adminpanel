<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

     /** @test */
     public function it_can_create_a_page()
     {
        
         // Disable middleware for this test
         $this->withoutMiddleware();
         
         $data = [
             'title' => 'Sample Page',
             'description' => 'This is a sample page description.',
             'banner' => 'sample-banner.jpg',
             'author' => 'John Doe',
         ];
 
         $response = $this->post(route('page.store'), $data);
         $response->assertStatus(200); // assuming you return 201 Created
 
         $this->assertDatabaseHas('pages', $data);
     }
 
     /** @test */
     public function it_can_read_a_page()
     {
         // Create a page entry
         $page = Page::create([
             'title' => 'Sample Page',
             'description' => 'This is a sample page description.',
             'banner' => 'sample-banner.jpg',
             'author' => 'John Doe',
         ]);
 
         // Make a GET request to the endpoint
         $response = $this->withoutMiddleware()->get(route('page.show', ['page' => $page->id]));
 
         // Assert the response status is OK (200)
         $response->assertStatus(200);
 
         // Assert the JSON response content
        //  $response->assertJson([
        //      'title' => 'Sample Page',
        //      'description' => 'This is a sample page description.',
        //      'banner' => 'sample-banner.jpg',
        //      'author' => 'John Doe',
        //  ]);
 
         // Assert the page entry exists in the database
         $this->assertDatabaseHas('pages', [
             'id' => $page->id,
             'title' => 'Sample Page',
             'description' => 'This is a sample page description.',
             'banner' => 'sample-banner.jpg',
             'author' => 'John Doe',
         ]);
     }
 
     /** @test */
     public function it_can_update_a_page()
     {
         // Create a page entry
         $page = Page::create([
             'title' => 'Sample Page',
             'description' => 'This is a sample page description.',
             'banner' => 'sample-banner.jpg',
             'author' => 'John Doe',
         ]);
 
         // Define the updated data
         $updatedData = [
             'title' => 'Updated Page',
             'description' => 'This is an updated page description.',
             'banner' => 'updated-banner.jpg',
             'author' => 'Jane Doe',
         ];
 
         // Make a PUT request to the endpoint using the route helper
         $response = $this->withoutMiddleware()->put(route('page.update', ['page' => $page->id]), $updatedData);
 
         // Assert the response status is OK (200)
         $response->assertStatus(200);
 
         // Assert the JSON response content
        //  $response->assertJson($updatedData);
 
         // Assert the page entry exists in the database with the updated data
         $this->assertDatabaseHas('pages', array_merge(['id' => $page->id], $updatedData));
     }
 
     /** @test */
     public function it_can_delete_a_page()
     {
         // Create a page entry
         $page = Page::create([
             'title' => 'Sample Page',
             'description' => 'This is a sample page description.',
             'banner' => 'sample-banner.jpg',
             'author' => 'John Doe',
         ]);
 
         // Make a DELETE request to the endpoint using the route helper
         $response = $this->withoutMiddleware()->delete(route('page.destroy', ['page' => $page->id]));
 
         // Assert the response status is No Content (200)
         $response->assertStatus(200);
 
         // Assert the page entry is missing in the database
         $this->assertDatabaseMissing('pages', [
             'id' => $page->id,
         ]);
     }
}
