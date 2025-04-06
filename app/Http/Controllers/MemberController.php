<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::all();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'number' => 'required',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'password' => 'required|min:6',
        ]);

        $data = $request->except('password', 'image');
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('members', 'public');
        }

        Member::create($data);

        return redirect()->route('members.index')->with('success', 'Member berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'number' => 'required',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->except('password', 'image');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($member->image && Storage::disk('public')->exists($member->image)) {
                Storage::disk('public')->delete($member->image);
            }
            $data['image'] = $request->file('image')->store('members', 'public');
        }

        $member->update($data);

        return redirect()->route('members.index')->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        if ($member->image && Storage::disk('public')->exists($member->image)) {
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member berhasil dihapus.');
    }
}
