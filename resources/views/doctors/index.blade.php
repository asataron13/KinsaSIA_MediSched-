@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Doctors List</h1>
    <p>Browse our specialists and find the right doctor for you.</p>
</div>

{{-- Department filter --}}
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
    <a href="{{ route('doctors.index') }}"
       class="btn {{ !request('department') ? 'btn-green' : 'btn-outline' }}"
       style="font-size:0.85rem;padding:7px 16px;">All</a>
    @foreach($departments as $dept)
        <a href="{{ route('doctors.index', ['department' => $dept->id]) }}"
           class="btn {{ request('department') == $dept->id ? 'btn-green' : 'btn-outline' }}"
           style="font-size:0.85rem;padding:7px 16px;">{{ $dept->name }}</a>
    @endforeach
</div>

@if($doctors->isEmpty())
    <div class="card" style="text-align:center;padding:60px;">
        <p style="color:#5a8a6e;font-size:1rem;">No doctors found for this department.</p>
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;">
        @foreach($doctors as $doctor)
        @php
            $avail = $doctor->availability_status;
            $badgeClass = match($avail) {
                'Available'  => 'badge-available',
                'In Session' => 'badge-insession',
                'Day Off'    => 'badge-dayoff',
                default      => 'badge-available',
            };
        @endphp
        <div class="card" style="text-align:center;padding:30px 22px;margin-bottom:0;">

            {{-- Avatar --}}
            <div style="width:66px;height:66px;border-radius:50%;background:#27ae60;color:white;font-weight:800;font-size:1.2rem;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
            </div>

            <div style="font-weight:700;font-size:1.05rem;">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div>
            <div style="font-size:0.86rem;color:#27ae60;font-weight:600;margin-top:3px;">{{ $doctor->department->name }}</div>

            <div style="font-size:0.83rem;color:#5a8a6e;margin-top:6px;">
                {{ $doctor->experience_years }} {{ $doctor->experience_years === 1 ? 'year' : 'years' }} experience &nbsp;·&nbsp; ⭐ {{ number_format($doctor->rating,1) }}
            </div>

            {{-- Schedule --}}
            <div style="background:#f4faf6;border-radius:8px;padding:9px 12px;margin:14px 0;font-size:0.83rem;color:#3a5a48;">
                🕐 {{ \Carbon\Carbon::parse($doctor->schedule_start)->format('h:i A') }}
                &ndash;
                {{ \Carbon\Carbon::parse($doctor->schedule_end)->format('h:i A') }}
            </div>

            <span class="badge {{ $badgeClass }}">{{ $avail }}</span>

            <div style="margin-top:18px;">
                @if($avail !== 'Day Off')
                    <a href="{{ route('appointments.book') }}" class="btn btn-green" style="width:100%;display:block;padding:9px;">
                        Book Appointment
                    </a>
                @else
                    <span class="btn btn-gray" style="width:100%;display:block;padding:9px;">Not Available Today</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
