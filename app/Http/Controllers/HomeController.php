<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredGenres = [
            [
                'name'      => 'Fiksi',
                'icon'      => 'bi-journal-richtext',
                'desc'      => 'Sastra klasik dunia, novel kontemporer, dan cerita fiksi penuh makna yang memikat.',
                'filter_id' => 1
            ],
            [
                'name'      => 'Non-Fiksi',
                'icon'      => 'bi-globe-americas',
                'desc'      => 'Biografi tokoh, sejarah peradaban, filsafat, dan pemikiran sosial kontemporer.',
                'filter_id' => 2
            ],
            [
                'name'      => 'Fantasi',
                'icon'      => 'bi-stars',
                'desc'      => 'Mitos epik, realisme magis, petualangan kerajaan kuno, dan dunia penuh keajaiban.',
                'filter_id' => 3
            ],
            [
                'name'      => 'Romansa',
                'icon'      => 'bi-heart',
                'desc'      => 'Kisah cinta mendalam, puisi klasik, perjalanan emosional, dan relasi manusia modern.',
                'filter_id' => 4
            ],
            [
                'name'      => 'Sains',
                'icon'      => 'bi-binoculars',
                'desc'      => 'Astrofisika, kosmologi, biologi, ilmu saraf, dan penjelajahan rahasia semesta.',
                'filter_id' => 5
            ],
            [
                'name'      => 'Teknologi',
                'icon'      => 'bi-cpu',
                'desc'      => 'Rekayasa perangkat lunak, arsitektur sistem, pemrograman, dan inovasi kecerdasan buatan.',
                'filter_id' => 6
            ],
        ];

        $editorPicks = Book::with('category')->whereIn('id', [3, 4, 10, 15])->get();
        if ($editorPicks->isEmpty()) {
            $editorPicks = Book::with('category')->take(4)->get();
        }

        $newArrivals = Book::with('category')->orderByDesc('id')->take(4)->get();
        $bestSellers = Book::with('category')->orderBy('stock', 'asc')->orderBy('id', 'asc')->take(4)->get();

        return view('home', compact('featuredGenres', 'editorPicks', 'newArrivals', 'bestSellers'));
    }
}
