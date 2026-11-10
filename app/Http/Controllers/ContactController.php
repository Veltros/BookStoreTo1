<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $userMessages = collect();
        $totalMessages = 0;
        $repliedCount = 0;

        if (auth()->check()) {
            $userMessages = Message::with('admin')
                ->where(function ($q) {
                    $q->where('user_id', auth()->id())
                      ->orWhere('email', auth()->user()->email);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            $totalMessages = $userMessages->count();
            $repliedCount = $userMessages->whereNotNull('admin_reply')->where('admin_reply', '!=', '')->count();
        }

        return view('contact', compact('userMessages', 'totalMessages', 'repliedCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
        ]);

        $name = auth()->check() ? auth()->user()->name : $request->name;
        $email = auth()->check() ? auth()->user()->email : $request->email;

        Message::create([
            'user_id' => auth()->id(),
            'name'    => $name,
            'email'   => $email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => 'Belum Dibaca',
        ]);

        return back()->with('success', 'Pesan chat Anda telah berhasil dikirim ke Administrator. Kami akan segera merespons!');
    }
}
