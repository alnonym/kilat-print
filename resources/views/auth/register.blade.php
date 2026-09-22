@extends('layouts.app')
@section('title', 'Daftar')

@section('content')
<div class="max-w-md mx-auto mt-8 bg-white border border-gray-300 rounded-lg shadow-lg p-8">
    <h1 class="text-3xl font-bold text-red-600 mb-2 text-center">Daftar</h1>
    <p class="text-center text-gray-600 mb-6">Buat akun Kilat Print</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-600" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-600" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telpon (Opsional)</label>
            <input type="text" name="no_hp" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-600">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <input type="password" name="password" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-600" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-600" required>
        </div>

        <button type="submit" class="w-full bg-red-600 text-white font-bold py-2 rounded hover:bg-red-700 transition">
            <i class="fa-solid fa-user-plus"></i> Daftar
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-4">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-red-600 font-semibold hover:underline">Login di sini</a>
    </p>
</div>
@endsection
