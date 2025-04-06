<?php

namespace App\Http\Controllers;

use App\Models\ChatTemplate;
use Illuminate\Http\Request;

class ChatTemplateController extends Controller
{
    public function index()
    {
        $templates = ChatTemplate::all();
        return view('chat_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('chat_templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        ChatTemplate::create($request->only('name', 'description'));
        return redirect()->route('chat-templates.index')->with('success', 'Template berhasil ditambahkan.');
    }

    public function edit(ChatTemplate $chatTemplate)
    {
        return view('chat_templates.edit', compact('chatTemplate'));
    }

    public function update(Request $request, ChatTemplate $chatTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $chatTemplate->update($request->only('name', 'description'));
        return redirect()->route('chat-templates.index')->with('success', 'Template berhasil diperbarui.');
    }

    public function destroy(ChatTemplate $chatTemplate)
    {
        $chatTemplate->delete();
        return redirect()->route('chat-templates.index')->with('success', 'Template berhasil dihapus.');
    }
}
