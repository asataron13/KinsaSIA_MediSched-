@extends('layouts.admin')
@section('page-title', 'Medical Records')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Medical Records</h1>
        <p>All patient medical records on file.</p>
    </div>
    <a href="{{ route('admin.records.create') }}" class="btn btn-green">+ Add Record</a>
</div>

<form method="GET" action="{{ route('admin.records.index') }}">
    <div class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name or code…" style="flex:1;min-width:200px;"/>
        <select name="type">
            <option value="">All Types</option>
            @foreach(['Medical History','Lab Results','Prescription','Billing'] as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All Statuses</option>
            @foreach(['Pending','Filed','Reviewed','Paid'] as $st)
                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-green btn-sm">Filter</button>
        <a href="{{ route('admin.records.index') }}" class="btn btn-outline btn-sm">Reset</a>
    </div>
</form>

@if($records->isEmpty())
    <div class="card empty-state">
        <div class="empty-icon">📋</div>
        <p>No records found.</p>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Type</th>
                    <th>Diagnosis</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $rec)
                @php $rs = strtolower($rec->status); @endphp
                <tr>
                    <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($rec->record_date)->format('M d, Y') }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $rec->patient->first_name }} {{ $rec->patient->last_name }}</div>
                        <div style="font-size:0.78rem;color:#5a8a6e;">{{ $rec->patient->patient_code }}</div>
                    </td>
                    <td>Dr. {{ $rec->doctor->first_name }} {{ $rec->doctor->last_name }}</td>
                    <td><span style="font-size:0.84rem;">{{ $rec->record_type }}</span></td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.88rem;" title="{{ $rec->diagnosis }}">
                        {{ $rec->diagnosis ?? '—' }}
                    </td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.88rem;" title="{{ $rec->notes }}">
                        {{ $rec->notes ?? '—' }}
                    </td>
                    <td>
                        <span class="badge badge-{{ in_array($rs, ['filed','reviewed','pending','paid']) ? $rs : 'pending' }}">
                            {{ $rec->status }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.records.destroy', $rec) }}"
                              onsubmit="return confirm('Delete this record permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-red btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $records->links() }}</div>
@endif

@endsection