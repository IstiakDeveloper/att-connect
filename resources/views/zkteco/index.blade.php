<!-- resources/views/zkteco/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ZKTeco ডিভাইস তালিকা</h5>
                    <a href="{{ route('zkteco.create') }}" class="btn btn-primary btn-sm">নতুন ডিভাইস যোগ করুন</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>আইডি</th>
                                <th>নাম</th>
                                <th>আইপি ঠিকানা</th>
                                <th>পোর্ট</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($devices as $device)
                                <tr>
                                    <td>{{ $device->id }}</td>
                                    <td>{{ $device->name }}</td>
                                    <td>{{ $device->ip_address }}</td>
                                    <td>{{ $device->port }}</td>
                                    <td>
                                        <a href="{{ route('zkteco.check-connection', $device->id) }}" class="btn btn-info btn-sm">কানেকশন চেক করুন</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোন ডিভাইস পাওয়া যায়নি</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
