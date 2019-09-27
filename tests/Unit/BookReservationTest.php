<?php

namespace Tests\Unit;

use App\Book;
use App\Reservation;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_checkout()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->check_out_at);
    }

    /** @test */
    public function a_book_can_returned()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $book->checkin($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertNotNull(Reservation::first()->check_out_at);
        $this->assertEquals(now(), Reservation::first()->check_in_at);
    }

    /** @test */
    public function if_not_checked_out_exception_id_thrown()
    {
        $this->expectException(\Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkin($user);
    }

    /** @test */
    public function a_user_can_checked_out_a_book_twice()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);
        $book->checkin($user);

        $book->checkout($user);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertNull(Reservation::find(2)->check_in_at);
        $this->assertEquals(now(), Reservation::find(2)->check_out_at);

        }
}
