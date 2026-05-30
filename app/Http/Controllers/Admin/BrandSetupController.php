<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandSetupController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // If already fully setup, no need to be here
        if (!empty($user->brand_name) && !empty($user->brand_logo)) {
            return redirect()->route('admin.dashboard');
        }

        return view('page.admin.setup.brand');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ], [
            'brand_logo.required' => 'Logo Laundry wajib diunggah!',
            'brand_logo.image' => 'File harus berupa gambar.',
            'brand_logo.max' => 'Ukuran maksimal logo adalah 2MB.',
        ]);

        $user = auth()->user();
        $user->brand_name = $request->brand_name;
        
        if ($request->hasFile('brand_logo')) {
            if ($user->brand_logo && file_exists(public_path($user->brand_logo))) {
                unlink(public_path($user->brand_logo));
            }
            
            $file = $request->file('brand_logo');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $destinationPath = public_path('image/brand-logo');
            
            $file->move($destinationPath, $filename);
            
            $user->brand_logo = 'image/brand-logo/' . $filename;
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Setup identitas Brand Laundry berhasil! Selamat datang.');
    }
}
