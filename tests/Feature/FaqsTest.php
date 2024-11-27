<?php

namespace Tests\Feature;

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FaqsTest extends TestCase
{    /** @test */
    public function it_can_create_a_faq()
    {

        $faq = FAQ::factory()->create();
        // Disable middleware for this test
        $this->withoutMiddleware();
        $data = [
            'question' => 'What is Laravel?',
            'answer' => 'Laravel is a web application framework with expressive, elegant syntax.',
            'section' => 'General',
            'sort' => 1,
            'status' => true,
        ];

        $response = $this->post(route('faq.store'), $data);
        // dd('HERE', $response->getContent());
        $response->assertStatus(200); // assuming you return 200 OK
        $this->assertDatabaseHas('faqs', array_merge(['id' => $faq->id], $data));
    }

    /** @test */
    public function it_can_read_a_faq()
    {
         // Create an FAQ entry
    $data = [
        'question' => 'What is Laravel?',
        'answer' => 'Laravel is a web application framework with expressive, elegant syntax.',
        'section' => 'General',
        'sort' => 1,
        'status' => true,
    ];
    $faq = Faq::create($data);
   
    // Make a GET request to the endpoint
    $response = $this->withoutMiddleware()->get(route('faq.show', ['faq' => $faq->id]));

    // Assert the response status is OK (200)
    $response->assertStatus(200);

    // Assert the JSON response content
    // $response->assertJson($data);

    // Assert the FAQ entry exists in the database
    $this->assertDatabaseHas('faqs', array_merge(['id' => $faq->id], $data));
    }

    /** @test */
    public function it_can_update_a_faq()
    {
        // Create an FAQ entry
        $faq = Faq::create([
            'question' => 'What is Laravel?',
            'answer' => 'Laravel is a web application framework with expressive, elegant syntax.',
            'section' => 'General',
            'sort' => 1,
            'status' => true,
        ]);
    
        // Define the updated data
        $updatedData = [
            'question' => 'What is PHPUnit?',
            'answer' => 'PHPUnit is a testing framework for PHP.',
            'section' => 'Testing',
            'sort' => 2,
            'status' => false,
        ];
    
        // Make a PUT request to the endpoint using the route helper
        $response = $this->withoutMiddleware()->put(route('faq.update', ['faq' => $faq->id]), $updatedData);
    
        // Assert the response status is OK (200)
        $response->assertStatus(200);
    
        // Assert the JSON response content
        // $response->assertJson($updatedData);
    
        // Assert the FAQ entry exists in the database with the updated data
        $this->assertDatabaseHas('faqs', array_merge(['id' => $faq->id], $updatedData));
    }
    

    /** @test */
    public function it_can_delete_a_faq()
    {
     // Create an FAQ entry
     $faq = Faq::create([
         'question' => 'What is Laravel?',
         'answer' => 'Laravel is a web application framework with expressive, elegant syntax.',
         'section' => 'General',
         'sort' => 1,
         'status' => true,
     ]);
    //  dd($faq->id);
     // Make a DELETE request to the endpoint using the route helper
     $response = $this->withoutMiddleware()->delete(route('faq.destroy', ['faq' => $faq->id]));
     // Assert the response status is No Content (200)
     $response->assertStatus(200);
 
     // Assert the FAQ entry is missing in the database
     $this->assertDatabaseMissing('faqs', [
         'id' => $faq->id,
     ]);
    }

}
