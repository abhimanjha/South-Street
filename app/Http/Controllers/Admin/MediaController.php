<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function index()
    {
        $files = [];
        $directories = ['uploads', 'products', 'blog', 'pages'];

        foreach ($directories as $dir) {
            $path = public_path($dir);
            if (File::exists($path)) {
                $files[$dir] = File::files($path);
            }
        }

        return view('admin.media.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'directory' => 'required|string'
        ]);

        $file = $request->file('file');
        $directory = $request->directory;

        // Ensure directory exists
        $path = public_path($directory);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($path, $filename);

        return response()->json([
            'success' => true,
            'file' => [
                'name' => $filename,
                'path' => $directory . '/' . $filename,
                'url' => asset($directory . '/' . $filename)
            ]
        ]);
    }

    public function destroy($media)
    {
        $path = public_path($media);

        if (File::exists($path)) {
            File::delete($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }
}
