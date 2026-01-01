@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Assigned Profiles (New Profiles)</h3>
                </div>
                <div class="card-body">
                    @if($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Profile Name</th>
                                        <th>Customer Name</th>
                                        <th>Mobile</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Assigned By</th>
                                        <th>Assigned At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->profile->id }}</td>
                                        <td>{{ $assignment->profile->name }}</td>
                                        <td>{{ $assignment->profile->customer_name }}</td>
                                        <td>{{ $assignment->profile->mobile }}</td>
                                        <td><span class="badge badge-info">{{ ucfirst($assignment->profile_type) }}</span></td>
                                        <td>
                                            <span class="badge 
                                                @if($assignment->status == 'assigned') badge-warning
                                                @elseif($assignment->status == 'contacted') badge-primary
                                                @elseif($assignment->status == 'converted') badge-success
                                                @else badge-danger
                                                @endif">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $assignment->assignedByStaff->name }}</td>
                                        <td>{{ $assignment->assigned_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <select class="form-control form-control-sm status-update" data-id="{{ $assignment->id }}">
                                                <option value="assigned" {{ $assignment->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                                <option value="contacted" {{ $assignment->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                <option value="converted" {{ $assignment->status == 'converted' ? 'selected' : '' }}>Converted</option>
                                                <option value="rejected" {{ $assignment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $assignments->links() }}
                        </div>
                    @else
                        <p class="text-center">No assigned profiles found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.status-update').on('change', function() {
        const assignmentId = $(this).data('id');
        const newStatus = $(this).val();

        if (confirm('Are you sure you want to update the status?')) {
            $.ajax({
                url: `/fresh-data/assignment/${assignmentId}/status`,
                method: 'PATCH',
                data: {
                    status: newStatus
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Error updating status');
                }
            });
        } else {
            location.reload();
        }
    });
});
</script>
@endpush
