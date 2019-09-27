<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorManagementTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created()
    {
        $this->post('/authors', $this->data());

        $author = Author::all();

        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1995/10/13', $author->first()->dob->format('Y/m/d'));
    }

    /** @test */
    public function an_author_name_is_required()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['name' => '']));
        
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_author_dob_is_required()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['dob' => '']));
        
        $response->assertSessionHasErrors('dob');
    }

    private function data()
    {
        return [
            'name' => 'Galang Adi',
            'dob' => '10/13/1995',
        ];
    }
}
