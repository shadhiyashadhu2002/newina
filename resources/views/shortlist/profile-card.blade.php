<div class="profile-card">
    @php
        $photoUrl = null;
        $placeholderUrl = asset('images/placeholder.svg');
        
        // Check if user has profile photo
        if ($shortlist->prospect_id) {
            $member = \App\Models\Member::find($shortlist->prospect_id);
            if ($member && $member->user) {
                if ($member->user->photo) {
                    $photoUrl = asset('storage/' . $member->user->photo);
                }
            }
        }
    @endphp

    <div class="profile-photo-container">
        <img 
            src="{{ $photoUrl ?? $placeholderUrl }}" 
            alt="Profile photo for {{ $shortlist->prospect_name ?? 'Profile' }}"
            class="profile-photo"
            onerror="this.src='{{ $placeholderUrl }}'"
        >
    </div>

    <div class="profile-info">
        <h3>{{ $shortlist->prospect_name ?? 'N/A' }}</h3>
        <p><strong>Member ID:</strong> {{ $shortlist->profile_id }}</p>
        <p><strong>Age:</strong> {{ $shortlist->prospect_age ?? 'N/A' }}</p>
        <p><strong>Location:</strong> {{ $shortlist->prospect_location ?? 'N/A' }}</p>
        <p><strong>Religion:</strong> {{ $shortlist->prospect_religion ?? 'N/A' }}</p>
        
        @if(!$photoUrl)
            <div class="alert alert-info">
                <small>📷 Photo not uploaded yet</small>
            </div>
        @endif
    </div>
</div>

<style>
    .profile-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin: 15px 0;
    }

    .profile-photo-container {
        margin-bottom: 15px;
    }

    .profile-photo {
        width: 200px;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        display: block;
    }

    .profile-info h3 {
        margin: 10px 0 15px 0;
        color: #333;
    }

    .profile-info p {
        margin: 8px 0;
        color: #666;
    }

    .alert {
        padding: 10px;
        border-radius: 4px;
        margin-top: 10px;
    }

    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
</style>
