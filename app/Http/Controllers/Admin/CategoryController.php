<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount(['books', 'orderItems'])->orderBy('name')->get();
        $editCategory = null;

        if ($request->filled('edit')) {
            $editCategory = Category::find($request->edit);
        }

        return view('admin.categories.index', compact('categories', 'editCategory'));
    }

    public function store(Request $request)
    {
        $trimmedName = trim($request->name ?? '');
        $request->merge(['name' => $trimmedName]);

        $request->validate([
            'name'        => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name'),
            ],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => "Kategori dengan nama '{$trimmedName}' sudah terdaftar dalam sistem.",
        ]);

        // Case-insensitive & trimmed duplicate safeguard
        $duplicateExists = Category::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($trimmedName)])->exists();
        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'name' => "Kategori dengan nama '{$trimmedName}' sudah terdaftar dalam sistem."
            ]);
        }

        Category::create([
            'name'        => $trimmedName,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$trimmedName}' berhasil ditambahkan.");
    }

    public function update(Request $request, Category $category)
    {
        $trimmedName = trim($request->name ?? '');
        $request->merge(['name' => $trimmedName]);

        $request->validate([
            'name'        => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => "Kategori dengan nama '{$trimmedName}' sudah terdaftar dalam sistem.",
        ]);

        // Case-insensitive & trimmed duplicate safeguard ignoring current category
        $duplicateExists = Category::where('id', '!=', $category->id)
            ->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($trimmedName)])
            ->exists();

        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'name' => "Kategori dengan nama '{$trimmedName}' sudah terdaftar dalam sistem."
            ]);
        }

        $category->update([
            'name'        => $trimmedName,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$trimmedName}' berhasil diperbarui.");
    }

    public function destroy(Category $category)
    {
        // Proteksi: Kategori tidak dapat dihapus jika ada pesanan user atau masih ada buku
        $orderItemsCount = $category->orderItems()->count();
        if ($orderItemsCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('danger', "Gagal menghapus! Kategori '{$category->name}' tidak dapat dihapus karena buku di dalamnya memiliki keterkaitan dengan {$orderItemsCount} riwayat transaksi pesanan pengguna.");
        }

        $booksCount = $category->books()->count();
        if ($booksCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('danger', "Gagal menghapus! Kategori '{$category->name}' tidak dapat dihapus karena masih memuat {$booksCount} buku. Silakan hapus atau pindahkan buku terlebih dahulu.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$name}' berhasil dihapus.");
    }
}
