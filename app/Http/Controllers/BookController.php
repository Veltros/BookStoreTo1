<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
        $categoryId = $request->filled('category') ? (int)$request->category : null;
        $priceRange = $request->get('price', '');
        $selectedAuthor = trim($request->get('author', ''));
        $selectedPublisher = trim($request->get('publisher', ''));
        $sort = $request->get('sort', 'newest');

        $categories = Category::withCount('books')->orderBy('id', 'asc')->get();
        $authors = Book::whereNotNull('author')->where('author', '!=', '')->distinct()->orderBy('author')->pluck('author');
        $publishers = Book::whereNotNull('publisher')->where('publisher', '!=', '')->distinct()->orderBy('publisher')->pluck('publisher');

        $query = Book::with('category');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($selectedAuthor)) {
            $query->where('author', $selectedAuthor);
        }

        if (!empty($selectedPublisher)) {
            $query->where('publisher', $selectedPublisher);
        }

        if ($priceRange === 'under100') {
            $query->where('price', '<', 100000);
        } elseif ($priceRange === '100to150') {
            $query->whereBetween('price', [100000, 150000]);
        } elseif ($priceRange === 'above150') {
            $query->where('price', '>', 150000);
        }

        switch ($sort) {
            case 'popular':
                $query->orderBy('stock', 'asc')->orderBy('id', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'newest':
            default:
                $query->orderByDesc('id');
                break;
        }

        $books = $query->paginate(12)->withQueryString();

        $hasActiveFilter = !empty($search) || !empty($categoryId) || !empty($priceRange) || !empty($selectedAuthor) || !empty($selectedPublisher) || $sort !== 'newest';

        return view('books.index', compact(
            'books', 'categories', 'authors', 'publishers',
            'search', 'categoryId', 'priceRange', 'selectedAuthor', 'selectedPublisher', 'sort', 'hasActiveFilter'
        ));
    }

    public function show(Book $book)
    {
        $book->load('category');
        $relatedBooks = Book::with('category')
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }
}
