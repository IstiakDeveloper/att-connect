<!-- resources/views/zkteco/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">নতুন ZKTeco ডিভাইস যোগ করুন</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('zkteco.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">ডিভাইসের নাম</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ip_address" class="form-label">আইপি ঠিকানা</label>
                            <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="ip_address" name="ip_address" value="{{ old('ip_address') }}" required>
                            @error('ip_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="port" class="form-label">পোর্ট</label>
                            <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" value="{{ old('port', 4370) }}" required>
                            @error('port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('zkteco.index') }}" class="btn btn-secondary">ফিরে যান</a>
                            <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
