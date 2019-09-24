<?php

namespace App\Http\Controllers;

use App\Book;

class BooksController extends Controller
{
    public function store()
    {
        $book = Book::create($this->validateReq());

        return redirect($book->path());
    }

    public function update(Book $book)
    {
        $book->update($this->validateReq());

        return redirect($book->path());
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect('/books');
    }

    protected function validateReq()
    {
        return request()->validate([
            'title' => 'required',
            'author' => 'required',
        ]);
    }
}
