@extends('layouts.admin')
@section('page-title', 'Patients')

@section('content')

<div class="page-header">
    <h1>Patients</h1>
    <p>All registered patient accounts.</p>
</div>

<form method="GET" action="{{ route('admin.patients.index') }}">
    <div class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or patient code…" style="flex:1;min-width:200px;"/>
        <select name="status">
            <option value="">All Statuses</option>
            @foreach(['Active','Inactive','Pending'] as $st)
                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-green btn-sm">Filter</button>
        <a href="{{ route('admin.patients.index') }}" class="btn btn-outline btn-sm">Reset</a>
    </div>
</form>

@if($patients->isEmpty())
    <div class="card empty-state">
        <div class="empty-icon">🧑‍⚕️</div>
        <p>No patients found.</p>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                @php
                    $s = strtolower($patient->status);
                    $badgeClass = $s === 'active' ? 'badge-active' : ($s === 'inactive' ? 'badge-inactive' : 'badge-pending');
                @endphp
                <tr>
                    <td><span style="font-family:monospace;color:#27ae60;font-weight:700;">{{ $patient->patient_code }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                        <div style="font-size:0.78rem;color:#5a8a6e;">{{ $patient->user->email ?? $patient->user->phone }}</div>
                    </td>
                    <td>{{ $patient->gender }}</td>
                    <td>{{ \Carbon\Carbon::parse($patient->birth_date)->age }} yrs</td>
                    <td style="font-size:0.88rem;">
                        @if($patient->user->email) <div>{{ $patient->user->email }}</div> @endif
                        @if($patient->user->phone) <div>{{ $patient->user->phone }}</div> @endif
                    </td>
                    <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.88rem;" title="{{ $patient->address }}">
                        {{ $patient->address }}
                    </td>
                    <td><span class="badge {{ $badgeClass }}">{{ $patient->status }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-blue btn-sm">View</a>
                            <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}"
                                  onsubmit="return confirm('Delete this patient account? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-red btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $patients->links() }}</div>
@endif

@endsection