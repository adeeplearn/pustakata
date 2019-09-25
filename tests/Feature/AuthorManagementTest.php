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
        $this->post('/author', [
            'name' => 'Galang Adi',
            'dob' => '10/13/1995'
        ]);

        $author = Author::all();

        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1995/10/13', $author->first()->dob->format('Y/m/d'));
    }
}
