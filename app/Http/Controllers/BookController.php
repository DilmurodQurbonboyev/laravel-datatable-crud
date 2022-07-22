<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::query()->latest()->get();

        if ($request->ajax()) {
            $data = Book::query()->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editBook">Edit</a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('book', compact('books'));
    }

    public function store(Request $request)
    {
        Book::query()->updateOrCreate(
            [
                'id' => $request->book_id
            ],
            [
                'title' => $request->title,
                'author' => $request->author
            ]
        );

        return response()->json(
            [
                'success' => 'Book saved successfully.'
            ]
        );
    }

    public function edit($id)
    {
        $book = Book::query()->findOrFail($id);
        return response()->json($book);
    }

    public function destroy($id)
    {
        Book::query()->findOrFail($id)->delete();

        return response()->json(
            [
                'success' => 'Book deleted successfully.'
            ]
        );
    }
}
