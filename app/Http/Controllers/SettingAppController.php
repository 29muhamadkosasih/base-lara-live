<?php

namespace App\Http\Controllers;

use App\Models\SettingApp;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class SettingAppController extends Controller
{

    public function index()
    {
        $setting = SettingApp::first(); // Ambil data pertama (karena hanya ada satu data)
        return view('setting-apps.index', compact('setting'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'brand' => 'required|string',
            'thumbnail' => 'required|string',
        ]);

        // Ambil data pertama dari tabel SettingApp
        $setting = SettingApp::firstOrFail();

        // Cek apakah ada file logo yang diunggah
        if ($request->hasFile('logo')) {
            // Hapus file lama jika ada
            if (!empty($setting->logo) && file_exists(public_path('storage/uploads/logos/' . $setting->logo))) {
                unlink(public_path('storage/uploads/logos/' . $setting->logo));
            }

            // Simpan file baru ke dalam public/storage/uploads/logos
            $file = $request->file('logo');
            $filename = time() . '-' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/uploads/logos');

            // Pastikan folder tujuan ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file ke folder tujuan
            $file->move($destinationPath, $filename);

            // Simpan hanya nama file di database
            $setting->logo = $filename;
        }

        // Update data lainnya
        $before = $setting->only(['brand', 'thumbnail', 'logo']);
        $setting->brand = $request->brand;
        $setting->thumbnail = $request->thumbnail;
        $setting->save();

        AuditLogger::log('setting-app.updated', $setting, 'Pengaturan aplikasi diperbarui.', [
            'before' => $before,
            'after' => $setting->only(['brand', 'thumbnail', 'logo']),
        ]);

        return redirect()->route('setting_apps.index')->with('success', 'Settings have been successfully updated!');
    }
}
