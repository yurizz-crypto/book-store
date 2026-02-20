@extends('layouts.app')

@section('title', 'Edit Profile | PageTurner')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Profile') }}
    </h2>
@endsection

@section('content')
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.update-address-information-form')
            @include('profile.partials.update-password-form')
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
