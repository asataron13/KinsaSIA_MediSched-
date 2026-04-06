@extends('layouts.admin')
@section('page-title', 'Doctors')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Doctors</h1>
        <p>Manage all doctor accounts and profiles.</p>
    </div>

    <a href="{{ route('admin.doctors.create') }}" class="btn btn-green">+ Add Doctor</a>
</div>

<div class="card" style="padding: 20px; margin-bottom: 22px;">
    <form method="GET" action="{{ route('admin.doctors.index') }}">
        <div class="filter-bar" style="margin-bottom:0;">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name…"
                style="flex:1;min-width:220px;"
            />

            <select name="status">
                <option value="">All Availability</option>
                @foreach(['Available','In Session','Day Off'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-green btn-sm">Filter</button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </div>
    </form>
</div>

@if($doctors->isEmpty())
    <div class="card empty-state">
        <div class="empty-icon">👨‍⚕️</div>
        <p>No doctors found.</p>
        <div style="margin-top:16px;">
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-green">Add First Doctor</a>
        </div>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Experience</th>
                    <th>Rating</th>
                    <th>Schedule</th>
                    <th>Availability</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $doctor)
                    @php
                        $av = strtolower(str_replace(' ','',$doctor->availability_status));
                        $badgeClass = match($av) {
                            'available' => 'badge-available',
                            'insession' => 'badge-insession',
                            'dayoff'    => 'badge-dayoff',
                            default     => 'badge-available',
                        };
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight:700;color:var(--text-dark);">
                                Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                            </div>
                        </td>

                        <td>{{ $doctor->department->name }}</td>

                        <td>
                            <span style="
                                display:inline-block;
                                background:#f4faf6;
                                color:#2f5e45;
                                padding:5px 10px;
                                border-radius:999px;
                                font-size:0.82rem;
                                font-weight:700;
                            ">
                                {{ $doctor->experience_years }} yrs
                            </span>
                        </td>

                        <td>
                            <span style="font-weight:700;color:#e67e22;">
                                ⭐ {{ number_format($doctor->rating, 1) }}
                            </span>
                        </td>

                        <td style="font-size:0.85rem;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($doctor->schedule_start)->format('h:i A') }}
                            – {{ \Carbon\Carbon::parse($doctor->schedule_end)->format('h:i A') }}
                        </td>

                        <td>
                            <span class="badge {{ $badgeClass }}">{{ $doctor->availability_status }}</span>
                        </td>

                        <td style="font-size:0.85rem;">
                            @if($doctor->user->email)
                                <div style="margin-bottom:2px;">{{ $doctor->user->email }}</div>
                            @endif
                            @if($doctor->user->phone)
                                <div>{{ $doctor->user->phone }}</div>
                            @endif
                        </td>

                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-blue btn-sm">View</a>
                                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-outline btn-sm">Edit</a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.doctors.destroy', $doctor) }}"
                                    onsubmit="return confirm('Delete Dr. {{ $doctor->last_name }}? This cannot be undone.')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-red btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $doctors->links() }}</div>
@endif

@endsection
