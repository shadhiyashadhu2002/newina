@extends('layouts.app')
@section('content')
<div class="dashboard-container">
    <div style="padding:24px;">
        <h2 style="color:#222;">Payroll Upload</h2>
    @if($errors->any())
        <div style="color:red; margin-bottom:12px;">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('payroll.upload') }}" enctype="multipart/form-data" style="margin-bottom:20px;">
        @csrf
        <div style="margin-bottom:8px;">
            <input type="file" name="payroll_file" accept=".csv,.xls,.xlsx" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    @if($processed)
        <form method="POST" action="{{ route('payroll.export') }}" style="margin-bottom:20px;">
            @csrf
            <button type="submit" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align:middle; margin-right:4px;">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                </svg>
                Export to CSV
            </button>
        </form>
    @endif
    @if($processed)
        <h3>Processed Results</h3>
        <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%; margin-top:20px;">
            <thead>
                <tr style="background-color:#ac0742; color:white; font-weight:bold;">
                    <th style="border-right:2px solid #333; min-width:100px;">Emp Code</th>
                    <th style="border-right:2px solid #333; min-width:150px;">Emp Name</th>
                    @for($d=1;$d<=31;$d++)
                        <th style="border-right:1px solid #ddd; text-align:center; width:80px;">{{ $d }}</th>  
                    @endfor
                    <th style="border-right:1px solid #333; text-align:center; min-width:120px;">Late Checkins</th>
                    <th style="border-right:1px solid #333; text-align:center; min-width:120px;">Early Checkouts</th>
                    <th style="border-right:1px solid #333; text-align:center; min-width:120px;">Extra Days (post 5:30 PM)</th>
                    <th style="border-right:1px solid #333; text-align:center; min-width:120px;">Absent Days</th>
                    <th style="border-right:1px solid #333; text-align:center; min-width:120px;">Half Days</th>
                    <th style="min-width:120px; text-align:center;">Total Leave (days)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processed as $p)
                    <tr style="border-bottom:2px solid #333;">
                        <td style="border-right:2px solid #333; font-weight:bold;">{{ $p['code'] }}</td>       
                        <td style="border-right:2px solid #333;">{{ $p['name'] }}</td>
                        @for($d=1;$d<=31;$d++)
                            @php
                                $day = $p['days'][$d] ?? null;
                                $display = '';
                                $bgColor = '';
                                if($day) {
                                    $status = $day['status'] ?? null;
                                    $in = $day['in'] ?? null;
                                    $out = $day['out'] ?? null;
                                    
                                    // Display status codes
                                    if($status === 'M') {
                                        $display = 'M';
                                        $bgColor = 'background-color:#ffcccc;';
                                    } elseif($status === 'WO') {
                                        $display = 'WO';
                                        $bgColor = 'background-color:#ccffcc;';
                                    } elseif($status === 'A') {
                                        $display = 'A';
                                        $bgColor = 'background-color:#ffcccc;';
                                    } elseif($in || $out) {
                                        $display = ($in? 'IN:'.$in : '') . ($out? '<br>OUT:'.$out : '');
                                        // Highlight late or early
                                        if(isset($day['is_late']) && $day['is_late']) {
                                            $bgColor = 'background-color:#fff3cd;';
                                        }
                                        if(isset($day['is_early']) && $day['is_early']) {
                                            $bgColor = 'background-color:#ffe0b2;';
                                        }
                                    }
                                }
                            @endphp
                            <td style="border-right:1px solid #ddd; vertical-align:top; text-align:center; font-size:12px; {{ $bgColor }}">{!! $display !!}</td>
                        @endfor
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['late_count'] }}</td>
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['early_count'] }}</td>
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['extra_count'] ?? 0 }}</td>
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['absent_count'] ?? 0 }}</td>
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['half_day_count'] ?? 0 }}</td>
                        <td style="text-align:center; font-weight:bold;">{{ $p['total_leave_days'] }}</td>     
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
