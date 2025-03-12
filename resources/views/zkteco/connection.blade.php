<!-- resources/views/zkteco/connection.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">ডিভাইস কানেকশন স্ট্যাটাস</h5>
                </div>

                <div class="card-body">
                    <h4>ডিভাইস তথ্য:</h4>
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><strong>নাম:</strong> {{ $device->name }}</li>
                        <li class="list-group-item"><strong>আইপি ঠিকানা:</strong> {{ $device->ip_address }}</li>
                        <li class="list-group-item"><strong>পোর্ট:</strong> {{ $device->port }}</li>
                    </ul>

                    <h4>কানেকশন স্ট্যাটাস:</h4>
                    <div class="mt-3">
                        @if ($connectionInfo['status'])
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check-circle"></i> ডিভাইসের সাথে সফলভাবে সংযোগ স্থাপন করা হয়েছে!</h5>

                                <div class="mt-3">
                                    <p><strong>ডিভাইসের নাম:</strong> {{ $connectionInfo['device_name'] }}</p>
                                    <p><strong>সিরিয়াল নম্বর:</strong> {{ $connectionInfo['serial_number'] }}</p>
                                    <p><strong>ডিভাইস সময়:</strong> {{ $connectionInfo['device_time'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-times-circle"></i> ডিভাইসের সাথে সংযোগ স্থাপন করা যায়নি!</h5>
                                <p><strong>ইরর মেসেজ:</strong> {{ $connectionInfo['message'] }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('zkteco.index') }}" class="btn btn-primary">ফিরে যান</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
