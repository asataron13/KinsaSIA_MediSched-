@extends('layouts.admin')
@section('page-title', 'Appointments')

@section('content')

<div class="page-header">
    <h1>Appointments</h1>
    <p>All appointments across every patient and doctor.</p>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.appointments.index') }}">
    <div class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name or code…" style="flex:1;min-width:200px;"/>
        <select name="status">
            <option value="">All Statuses</option>
            @foreach(['Pending','Confirmed','In Progress','Completed','Cancelled'] as $st)
                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-green btn-sm">Filter</button>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline btn-sm">Reset</a>
    </div>
</form>

{{-- Count tabs --}}
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
    @foreach([
        [''            , 'All',         $counts['all']],
        ['Pending'     , 'Pending',     $counts['pending']],
        ['Confirmed'   , 'Confirmed',   $counts['confirmed']],
        ['In Progress' , 'In Progress', $counts['inprogress']],
        ['Completed'   , 'Completed',   $counts['completed']],
        ['Cancelled'   , 'Cancelled',   $counts['cancelled']],
    ] as [$val, $label, $count])
    <a href="{{ route('admin.appointments.index', array_merge(request()->except('status','page'), $val ? ['status' => $val] : [])) }}"
       class="btn btn-sm {{ request('status') === $val ? 'btn-green' : 'btn-outline' }}">
        {{ $label }} ({{ $count }})
    </a>
    @endforeach
</div>

@if($appointments->isEmpty())
    <div class="card empty-state">
        <div class="empty-icon">📭</div>
        <p>No appointments found.</p>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Change Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                @php $s = strtolower(str_replace(' ','',$appt->status)); @endphp
                <tr>
                    <td style="color:#aaa;font-size:0.82rem;">{{ $appt->id }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $appt->patient->first_name }} {{ $appt->patient->last_name }}</div>
                        <div style="font-size:0.78rem;color:#5a8a6e;">{{ $appt->patient->patient_code }}</div>
                    </td>
                    <td>Dr. {{ $appt->doctor->first_name }} {{ $appt->doctor->last_name }}</td>
                    <td>{{ $appt->department->name }}</td>
                    <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>
                    <td>{{ $appt->type }}</td>
                    <td><span class="badge badge-{{ $s }}">{{ $appt->status }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.appointments.status', $appt) }}" style="display:flex;gap:6px;align-items:center;">
                            @csrf @method('PATCH')
                            <select name="status" style="padding:5px 8px;font-size:0.82rem;border:1.5px solid #d4e6da;border-radius:6px;outline:none;">
                                @foreach(['Pending','Confirmed','In Progress','Completed','Cancelled'] as $st)
                                    <option value="{{ $st }}" {{ $appt->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-blue btn-sm">Save</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.appointments.destroy', $appt) }}"
                              onsubmit="return confirm('Delete this appointment permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-red btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $appointments->links() }}</div>
@endif

@endsection