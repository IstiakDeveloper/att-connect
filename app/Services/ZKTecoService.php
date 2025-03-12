<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\DeviceIp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Rats\Zkteco\Lib\ZKTeco;

class ZKTecoService
{
    protected $zk;
    protected $deviceIp;

    public function __construct($ip = null)
    {
        $this->deviceIp = $ip ?? DeviceIp::first()->ip_address ?? null;

        if ($this->deviceIp) {
            $this->zk = new ZKTeco($this->deviceIp);
        }
    }

    /**
     * কানেকশন চেক করুন
     */
    public function checkConnection()
    {
        if (!$this->deviceIp) {
            return [
                'status' => false,
                'message' => 'কোন ডিভাইস আইপি পাওয়া যায়নি'
            ];
        }

        try {
            $connected = $this->zk->connect();

            if ($connected) {
                $info = [
                    'status' => true,
                    'device_name' => $this->zk->deviceName(),
                    'serial_number' => $this->zk->serialNumber(),
                    'device_time' => $this->zk->getTime()
                ];

                $this->zk->disconnect();
                return $info;
            }

            return ['status' => false, 'message' => 'কানেকশন সফল হয়নি'];
        } catch (\Exception $e) {
            Log::error('ZKTeco কানেকশন ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * ডিভাইস পুনরায় চালু করুন
     */
    public function restartDevice()
    {
        try {
            if ($this->zk->connect()) {
                $this->zk->restart();
                return ['status' => true, 'message' => 'ডিভাইস সফলভাবে পুনরায় চালু হয়েছে'];
            }

            return ['status' => false, 'message' => 'ডিভাইস সংযোগ করা যায়নি'];
        } catch (\Exception $e) {
            Log::error('ZKTeco পুনরায় চালু করার ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * ডিভাইসের সকল উপস্থিতি লগ পরিষ্কার করুন
     */
    public function clearAttendance()
    {
        try {
            if ($this->zk->connect()) {
                $this->zk->clearAttendance();
                $this->zk->disconnect();
                return ['status' => true, 'message' => 'উপস্থিতি লগ সফলভাবে পরিষ্কার করা হয়েছে'];
            }

            return ['status' => false, 'message' => 'ডিভাইস সংযোগ করা যায়নি'];
        } catch (\Exception $e) {
            Log::error('ZKTeco লগ পরিষ্কার ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * সকল ব্যবহারকারী ডাটা আনুন
     */
    public function getUsers()
    {
        try {
            if ($this->zk->connect()) {
                $users = $this->zk->getUser();
                $this->zk->disconnect();
                return ['status' => true, 'data' => $users];
            }

            return ['status' => false, 'message' => 'ডিভাইস সংযোগ করা যায়নি'];
        } catch (\Exception $e) {
            Log::error('ZKTeco ব্যবহারকারী ডাটা ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * সকল উপস্থিতি লগ আনুন
     */
    public function getAttendance()
    {
        try {
            if ($this->zk->connect()) {
                $logs = $this->zk->getAttendance();
                $this->zk->disconnect();
                return ['status' => true, 'data' => $logs];
            }

            return ['status' => false, 'message' => 'ডিভাইস সংযোগ করা যায়নি'];
        } catch (\Exception $e) {
            Log::error('ZKTeco উপস্থিতি লগ ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * ডিভাইস থেকে উপস্থিতি লগ ডাটাবেজে সিঙ্ক করুন
     */
    public function syncAttendance()
    {
        try {
            $attendanceData = $this->getAttendance();

            if (!$attendanceData['status']) {
                return $attendanceData;
            }

            $syncCount = 0;
            $logs = $attendanceData['data'];

            foreach ($logs as $log) {
                // পূর্বে এই রেকর্ড আছে কিনা চেক করুন
                $exists = Attendance::where('user_id', $log['id'])
                    ->where('attendance_time', Carbon::parse($log['timestamp']))
                    ->exists();

                if (!$exists) {
                    Attendance::create([
                        'user_id' => $log['id'],
                        'state' => $log['state'],
                        'attendance_time' => Carbon::parse($log['timestamp']),
                        'device_ip' => $this->deviceIp
                    ]);

                    $syncCount++;
                }
            }

            return [
                'status' => true,
                'message' => $syncCount . ' টি নতুন রেকর্ড সিঙ্ক করা হয়েছে',
                'count' => $syncCount
            ];
        } catch (\Exception $e) {
            Log::error('ZKTeco সিঙ্ক ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * ডিভাইসের নাম পান
     */
    public function getDeviceName()
    {
        try {
            if ($this->zk->connect()) {
                $name = $this->zk->deviceName();
                $this->zk->disconnect();
                return ['status' => true, 'name' => $name];
            }

            return ['status' => false, 'message' => 'ডিভাইস সংযোগ করা যায়নি'];
        } catch (\Exception $e) {
            Log::error('ZKTeco ডিভাইস নাম ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * ডিভাইসের সময় পান
     */
    public function getDeviceTime()
    {
        try {
            if ($this->zk->connect()) {
                $time = $this->zk->getTime();
                $this->zk->disconnect();
                return ['status' => true, 'time' => $time];
            }

            return ['status' => false, 'message' => 'ডিভাইস সংযোগ করা যায়নি'];
        } catch (\Exception $e) {
            Log::error('ZKTeco ডিভাইস সময় ইরর: ' . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
