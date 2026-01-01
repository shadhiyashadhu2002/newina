@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>User Profiles</h1>
    <p>Welcome, {{ $user->name ?? $user->email }}!</p>
    <div class="alert alert-info mt-4">
        This is the user profiles page. You can customize this view to show user-specific profiles or information.
    </div>
</div>
@endsection
