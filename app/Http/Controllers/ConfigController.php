<?php

namespace App\Http\Controllers;

use App\Models\Basic;
use Illuminate\Support\Facades\Cache;

class ConfigController extends Controller
{
    public function getConfig()
    {
        $config = Cache::remember('basic_config', 60, function () {
            return Basic::all()->pluck('value', 'key')->toArray();
        });

        // Format asset URL untuk file yang butuh path gambar
        $imageKeys = ['theme_img_light', 'theme_img_dark', 'logo_light', 'logo_dark'];
        foreach ($imageKeys as $key) {
            if (!empty($config[$key])) {
                $config[$key] = asset('storage/' . $config[$key]);
            }
        }

        return response()->json($config);
    }
}
