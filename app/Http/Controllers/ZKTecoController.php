<?php

namespace App\Http\Controllers;

use App\Models\ZKTecoDevice;
use App\Services\ZKTecoService;
use Illuminate\Http\Request;

class ZKTecoController extends Controller
{
    /**
     * ডিভাইস তালিকা দেখানো
     */
    public function index()
    {
        $devices = ZKTecoDevice::all();
        return view('zkteco.index', compact('devices'));
    }

    /**
     * নতুন ডিভাইস যোগ করার ফর্ম দেখানো
     */
    public function create()
    {
        return view('zkteco.create');
    }

    /**
     * নতুন ডিভাইস সংরক্ষণ করুন
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'port' => 'required|integer',
        ]);

        ZKTecoDevice::create($validated);

        return redirect()->route('zkteco.index')
            ->with('success', 'ডিভাইস সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * ডিভাইস কানেকশন চেক করুন
     */
    public function checkConnection($id)
    {
        $device = ZKTecoDevice::findOrFail($id);

        $zkService = new ZKTecoService($device->ip_address, $device->port);
        $connectionInfo = $zkService->checkConnection();

        return view('zkteco.connection', compact('device', 'connectionInfo'));
    }
}
