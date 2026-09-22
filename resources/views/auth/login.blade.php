@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto mt-8 bg-white border border-gray-300 rounded-lg shadow-lg p-8">
    <h1 class="text-3xl font-bold text-red-600 mb-2 text-center">Masuk</h1>
    <p class="text-center text-gray-600 mb-6">Ke Kilat Print</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-600" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <input type="password" name="password" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-600" required>
        </div>

        <button type="submit" class="w-full bg-red-600 text-white font-bold py-2 rounded hover:bg-red-700 transition">
            <i class="fa-solid fa-sign-in-alt"></i> Masuk
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-4">
        Belum punya akun? <a href="{{ route('register') }}" class="text-red-600 font-semibold hover:underline">Daftar sekarang</a>
    </p>
</div>
@endsection
