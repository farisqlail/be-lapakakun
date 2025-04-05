<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        return view('banners.index', ['banners' => Banner::all()]);
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $path,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner berhasil diunggah.');
    }

    public function edit(Banner $banner)
    {
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|max:2048',
            ]);

            $data = ['title' => $request->title];

            if ($request->hasFile('image')) {
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                $data['image'] = $request->file('image')->store('photos', 'public');
            }

            $banner->update($data);

            return redirect()->route('banners.index')->with('success', 'Banner berhasil diperbarui.');
        } catch (\Throwable $e) {
            \Log::error('Gagal update banner: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui banner: ' . $e->getMessage());
        }
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner berhasil dihapus.');
    }
}
