<?php

namespace Tests\Feature;

use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_add_to_library()
    {
        $response = $this->post('/books', [
           'title' => 'Test Driven Book',
           'author' => 'Galang Adi'
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Galang Adi'
        ]);

        $response->assertSessionHasErrors('title');

        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function a_author_is_required()
    {
        $response = $this->post('/books', [
            'title' => 'New Book',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');

        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->post('/books', [
            'title' => 'Cool Books',
            'author' => 'Galang'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' .$book->id, [
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {

        $this->post('/books', [
            'title' => 'Cool Books',
            'author' => 'Galang'
        ]);

        $this->assertCount(1, Book::all());

        $book = Book::first();

        $response = $this->delete($book->path());
        
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}
