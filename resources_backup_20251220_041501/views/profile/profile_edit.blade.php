        
@extends('layouts.app')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #f8f9fa 0%, #e8f5f0 50%, #d1f2eb 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .edit-profiles-wrapper {
        min-height: 100vh;
        padding: 40px 20px;
    }

    .edit-profiles-container {
        max-width: 600px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .edit-profiles-header {
        margin-bottom: 40px;
    }

    .edit-profiles-header h1 {
        font-size: 28px;
        font-weight: 300;
        color: #6b7280;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .edit-profiles-header-line {
        height: 1px;
        background: linear-gradient(90deg, #d1d5db 0%, transparent 100%);

<div class="edit-profile-form">
    <div class="edit-profile-header">Edit Profile</div>
    <form action="{{ route('profile.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="code">Profile ID</label>
            <input type="text" id="code" name="code" class="form-control" value="{{ $user->code }}" readonly>
        </div>
        <div class="form-group">
            <label for="first_name">Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->first_name }}">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <input type="text" id="gender" name="gender" class="form-control" value="{{ $gender ?? $user->gender }}" readonly>
        </div>
        <div class="form-group">
            <label for="created_at">Registration Date</label>
            <input type="text" id="created_at" name="created_at" class="form-control" value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}" readonly>
        </div>
        <div class="form-group">
            <label for="verified_date">Verified Date</label>
            <input type="text" id="verified_date" name="verified_date" class="form-control" value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}" readonly>
        </div>
        <div class="form-group">
            <label for="phone">Mobile Number 1</label>
            <input type="tel" id="phone" name="phone" class="form-control" value="{{ $user->phone }}">
        </div>
        <div class="form-group">
            <label for="phone2">Mobile Number 2</label>
            <input type="tel" id="phone2" name="phone2" class="form-control" value="{{ $user->phone2 }}">
        </div>
        <div class="form-group">
            <label for="whatsapp_number">WhatsApp Number</label>
            <input type="tel" id="whatsapp_number" name="whatsapp_phone" class="form-control" value="{{ $user->whatsapp_number }}">
        </div>
        <div class="form-group">
            <label for="comments">Comments</label>
            <textarea id="comments" name="comments" class="form-control" rows="3">{{ $user->comments }}</textarea>
        </div>
        <button type="submit" class="btn-primary">Update</button>
    </form>
</div>
@endsection
