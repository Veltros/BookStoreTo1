<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        User::updateOrCreate(
            ['email' => 'admin@bookstore.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'phone'    => '081234567890',
                'address'  => 'Kantor Pusat The Book Atelier, Jakarta',
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('user1234'),
                'phone'    => '089876543210',
                'address'  => 'Jl. Merdeka No. 45, Bandung',
                'role'     => 'user',
            ]
        );

        // 2. Seed Categories
        $categories = [
            1 => ['name' => 'Fiction', 'description' => 'Literary classics, contemporary novels, drama, and captivating short stories.'],
            2 => ['name' => 'Non-Fiction', 'description' => 'Biographies, memoirs, history, philosophy, and thought-provoking essays.'],
            3 => ['name' => 'Fantasy', 'description' => 'Mythological epics, magical realism, epic quests, and alternate worlds.'],
            4 => ['name' => 'Romance', 'description' => 'Heartfelt tales of love, poetry, emotional journeys, and modern relationships.'],
            5 => ['name' => 'Science', 'description' => 'Astrophysics, cosmology, biology, neuroscience, and natural discoveries.'],
            6 => ['name' => 'Technology', 'description' => 'Software engineering, computer architecture, AI, and digital innovation.'],
            7 => ['name' => 'Business & Economics', 'description' => 'Financial wisdom, entrepreneurship, market strategies, and investment philosophies.'],
            8 => ['name' => 'Self Development', 'description' => 'Habit formation, personal productivity, mindfulness, and cognitive growth.'],
        ];

        foreach ($categories as $id => $cat) {
            Category::updateOrCreate(['id' => $id], $cat);
        }

        // 3. Seed Books
        $books = [
            [
                'id'          => 1,
                'category_id' => 6,
                'title'       => 'Pemrograman Web Lengkap dengan PHP & MySQL',
                'author'      => 'Rian Suryanto',
                'publisher'   => 'Informatika Press',
                'isbn'        => '978-602-851-998-1',
                'year'        => 2023,
                'price'       => 95000.00,
                'stock'       => 15,
                'description' => 'Buku panduan lengkap membangun aplikasi web interaktif dari dasar hingga mahir menggunakan PHP native dan basis data MySQL.',
                'cover_image' => 'book_php.jpg',
            ],
            [
                'id'          => 2,
                'category_id' => 6,
                'title'       => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author'      => 'Robert C. Martin',
                'publisher'   => 'Prentice Hall',
                'isbn'        => '978-013-235-088-4',
                'year'        => 2021,
                'price'       => 145000.00,
                'stock'       => 8,
                'description' => 'Prinsip-prinsip penulisan kode yang bersih, mudah dipelihara, dan sesuai kaidah best practice rekayasa perangkat lunak internasional.',
                'cover_image' => 'book_cleancode.jpg',
            ],
            [
                'id'          => 3,
                'category_id' => 1,
                'title'       => 'Laskar Pelangi',
                'author'      => 'Andrea Hirata',
                'publisher'   => 'Bentang Pustaka',
                'isbn'        => '978-979-306-279-2',
                'year'        => 2020,
                'price'       => 89000.00,
                'stock'       => 20,
                'description' => 'Kisah inspiratif tentang perjuangan sepuluh anak di Belitung dalam mengejar mimpi, persahabatan, dan pendidikan.',
                'cover_image' => 'book_laskar.jpg',
            ],
            [
                'id'          => 4,
                'category_id' => 1,
                'title'       => 'Bumi Manusia',
                'author'      => 'Pramoedya Ananta Toer',
                'publisher'   => 'Lentera Dipantara',
                'isbn'        => '978-979-973-120-5',
                'year'        => 2019,
                'price'       => 110000.00,
                'stock'       => 12,
                'description' => 'Karya masterpiece sastra Indonesia yang mengisahkan perjuangan Minke di era pergolakan kolonialisme.',
                'cover_image' => 'book_bumi.jpg',
            ],
            [
                'id'          => 5,
                'category_id' => 7,
                'title'       => 'The Psychology of Money',
                'author'      => 'Morgan Housel',
                'publisher'   => 'Harriman House',
                'isbn'        => '978-085-719-768-9',
                'year'        => 2022,
                'price'       => 98000.00,
                'stock'       => 18,
                'description' => 'Pelajaran abadi mengenai kekayaan, ketamakan, dan kebahagiaan dalam mengelola uang, risiko, dan investasi.',
                'cover_image' => 'book_money.jpg',
            ],
            [
                'id'          => 6,
                'category_id' => 8,
                'title'       => 'Atomic Habits: Tiny Changes, Remarkable Results',
                'author'      => 'James Clear',
                'publisher'   => 'Avery Publishing',
                'isbn'        => '978-073-521-129-2',
                'year'        => 2021,
                'price'       => 108000.00,
                'stock'       => 25,
                'description' => 'Cara mudah dan terbukti membentuk kebiasaan baik dan menghilangkan kebiasaan buruk setiap hari secara konsisten.',
                'cover_image' => 'book_atomichabits.jpg',
            ],
            [
                'id'          => 7,
                'category_id' => 1,
                'title'       => 'The Great Gatsby',
                'author'      => 'F. Scott Fitzgerald',
                'publisher'   => 'Scribner Classic',
                'isbn'        => '978-074-327-356-5',
                'year'        => 2020,
                'price'       => 125000.00,
                'stock'       => 14,
                'description' => 'A timeless portrait of the Roaring Twenties, exploring obsession, love, grandeur, and the disillusionment of the American Dream.',
                'cover_image' => 'book_gatsby.jpg',
            ],
            [
                'id'          => 8,
                'category_id' => 2,
                'title'       => 'Sapiens: A Brief History of Humankind',
                'author'      => 'Yuval Noah Harari',
                'publisher'   => 'Harper',
                'isbn'        => '978-006-231-609-7',
                'year'        => 2018,
                'price'       => 165000.00,
                'stock'       => 16,
                'description' => 'From a renowned historian comes a groundbreaking narrative of humanity’s creation, evolution, and future trajectory.',
                'cover_image' => 'book_sapiens.jpg',
            ],
            [
                'id'          => 9,
                'category_id' => 2,
                'title'       => 'Educated: A Memoir',
                'author'      => 'Tara Westover',
                'publisher'   => 'Random House',
                'isbn'        => '978-039-959-050-4',
                'year'        => 2019,
                'price'       => 138000.00,
                'stock'       => 10,
                'description' => 'An unforgettable memoir about a young girl who leaves her survivalist family in rural Idaho and embarks on a journey of self-invention through education.',
                'cover_image' => 'book_educated.jpg',
            ],
            [
                'id'          => 10,
                'category_id' => 3,
                'title'       => 'The Hobbit',
                'author'      => 'J.R.R. Tolkien',
                'publisher'   => 'George Allen & Unwin',
                'isbn'        => '978-054-792-822-7',
                'year'        => 2021,
                'price'       => 149000.00,
                'stock'       => 12,
                'description' => 'The glorious prelude to The Lord of the Rings, following Bilbo Baggins as he is swept into an epic quest to reclaim the lost Dwarf Kingdom of Erebor.',
                'cover_image' => 'book_hobbit.jpg',
            ],
            [
                'id'          => 11,
                'category_id' => 3,
                'title'       => 'The Name of the Wind',
                'author'      => 'Patrick Rothfuss',
                'publisher'   => 'DAW Books',
                'isbn'        => '978-075-640-474-1',
                'year'        => 2020,
                'price'       => 155000.00,
                'stock'       => 9,
                'description' => 'Told in Kvothe’s own voice, this is the tale of the magically gifted young man who grows to be the most notorious wizard his world has ever seen.',
                'cover_image' => 'book_wind.jpg',
            ],
            [
                'id'          => 12,
                'category_id' => 4,
                'title'       => 'Pride and Prejudice',
                'author'      => 'Jane Austen',
                'publisher'   => 'Penguin Classics',
                'isbn'        => '978-014-143-951-8',
                'year'        => 2019,
                'price'       => 95000.00,
                'stock'       => 22,
                'description' => 'The romantic clash between the opinionated Elizabeth Bennet and her proud aristocratic suitor, Mr. Darcy, in a sparkling social comedy.',
                'cover_image' => 'book_pride.jpg',
            ],
            [
                'id'          => 13,
                'category_id' => 4,
                'title'       => 'Normal People',
                'author'      => 'Sally Rooney',
                'publisher'   => 'Faber & Faber',
                'isbn'        => '978-198-482-217-8',
                'year'        => 2021,
                'price'       => 115000.00,
                'stock'       => 15,
                'description' => 'An intimate, exquisitely nuanced story of mutual fascination, subtle romance, and growing up between Connell and Marianne.',
                'cover_image' => 'book_normalpeople.jpg',
            ],
            [
                'id'          => 14,
                'category_id' => 5,
                'title'       => 'Astrophysics for People in a Hurry',
                'author'      => 'Neil deGrasse Tyson',
                'publisher'   => 'W. W. Norton & Co.',
                'isbn'        => '978-039-360-939-4',
                'year'        => 2019,
                'price'       => 110000.00,
                'stock'       => 18,
                'description' => 'What is the nature of space and time? How do we fit within the cosmos? Essential cosmic wisdom delivered with wit and clarity.',
                'cover_image' => 'book_astrophysics.jpg',
            ],
            [
                'id'          => 15,
                'category_id' => 5,
                'title'       => 'A Brief History of Time',
                'author'      => 'Stephen Hawking',
                'publisher'   => 'Bantam Books',
                'isbn'        => '978-055-338-016-3',
                'year'        => 2018,
                'price'       => 120000.00,
                'stock'       => 11,
                'description' => 'A landmark volume in science writing by one of the greatest minds of our era, exploring black holes, gravitational singularities, and the origin of our universe.',
                'cover_image' => 'book_hawking.jpg',
            ],
            [
                'id'          => 16,
                'category_id' => 6,
                'title'       => 'Designing Data-Intensive Applications',
                'author'      => 'Martin Kleppmann',
                'publisher'   => 'O\'Reilly Media',
                'isbn'        => '978-144-937-332-0',
                'year'        => 2022,
                'price'       => 195000.00,
                'stock'       => 7,
                'description' => 'The definitive guide to the principles and architectures of scalable, reliable, and maintainable systems for modern data computing.',
                'cover_image' => 'book_ddia.jpg',
            ],
        ];

        foreach ($books as $b) {
            Book::updateOrCreate(['id' => $b['id']], $b);
        }
    }
}
