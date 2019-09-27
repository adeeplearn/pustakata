<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_add_to_library()
    {
        $response = $this->post('/books', $this->data());

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', array_merge($this->data(), ['title'=>'']));

        $response->assertSessionHasErrors('title');

        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function an_author_is_required()
    {
        $response = $this->post('/books', array_merge($this->data(), ['author_id'=>'']));

        $response->assertSessionHasErrors('author_id');

        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch('/books/' .$book->id, [
            'title' => 'New Title',
            'author_id' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->post('/books', $this->data());

        $this->assertCount(1, Book::all());

        $book = Book::first();

        $response = $this->delete($book->path());
        
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }

    /** @test */
    public function a_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();
        
        $this->post('/books', [
            'title' => 'Cool Books',
            'author_id' => 'Galang',
        ]);

        $book = Book::first();
        $author = Author::first();        
        
        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data(){
        return [
            'title' => 'Test Driven Book',
            'author_id' => 'Galang Adi',
        ];
    }
}
