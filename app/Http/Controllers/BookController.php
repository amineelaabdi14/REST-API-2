<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $book=Book::all();
        return response()->json($book);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $book= Book::create($request->all());
        return response()->json($book);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $book= Book::find($id);
        return response()->json($book);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $book = Book::find($id);
        $book->title=$request->title;
        $book->category_id=$request->category_id;
        return response()->json($book);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $book =  Book::find($id);
        $book->delete();
        return response()->json(['message','Book Deleted']);

    }
}
