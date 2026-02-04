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
                    <th style="border-right:1px solid #333; text-align:center; min-width:140px;">Extra Days (post 17:30)</th>
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
                                if($day) {
                                    $in = $day['in'] ?? null;
                                    $out = $day['out'] ?? null;
                                    if($in || $out) {
                                        $display = ($in? 'IN:'.$in : '') . ($out? '<br>OUT:'.$out : '');
                                    }
                                }
                            @endphp
                            <td style="border-right:1px solid #ddd; vertical-align:top; text-align:center; font-size:12px;">{!! $display !!}</td>
                        @endfor
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['late_count'] }}</td>
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['early_count'] }}</td>
                        <td style="border-right:1px solid #333; text-align:center; font-weight:bold;">{{ $p['extra_count'] ?? 0 }}</td>
                        <td style="text-align:center; font-weight:bold;">{{ $p['total_leave_days'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
