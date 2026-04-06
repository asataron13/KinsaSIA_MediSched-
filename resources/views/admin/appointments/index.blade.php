@extends('layouts.admin')
@section('page-title', 'Appointments')

@section('content')

<div style="display: flex; flex-direction: column; gap: 22px;">

    <div class="page-header" style="margin-bottom: 0;">
        <h1>Appointments</h1>
        <p>All appointments across every patient and doctor.</p>
    </div>

    {{-- Filters --}}
    <div class="card" style="padding: 20px;">
        <form method="GET" action="{{ route('admin.appointments.index') }}">
            <div class="filter-bar" style="margin-bottom: 0;">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search patient name or code…"
                    style="flex:1;min-width:220px;"
                />

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
    </div>

    {{-- Count tabs --}}
    <div class="status-tabs">
        @foreach([
            [''            , 'All',         $counts['all']],
            ['Pending'     , 'Pending',     $counts['pending']],
            ['Confirmed'   , 'Confirmed',   $counts['confirmed']],
            ['In Progress' , 'In Progress', $counts['inprogress']],
            ['Completed'   , 'Completed',   $counts['completed']],
            ['Cancelled'   , 'Cancelled',   $counts['cancelled']],
        ] as [$val, $label, $count])
            <a
                href="{{ route('admin.appointments.index', array_merge(request()->except('status','page'), $val ? ['status' => $val] : [])) }}"
                class="status-pill {{ request('status') === $val ? 'status-pill-active' : '' }}"
            >
                {{ $label }} <span>({{ $count }})</span>
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
                            <td style="color:#8aa395;font-size:0.82rem;font-weight:600;">
                                {{ $appt->id }}
                            </td>

                            <td>
                                <div style="font-weight:700;color:var(--text-dark);">
                                    {{ $appt->patient->first_name }} {{ $appt->patient->last_name }}
                                </div>
                                <div style="font-size:0.78rem;color:#5a8a6e;margin-top:2px;">
                                    {{ $appt->patient->patient_code }}
                                </div>
                            </td>

                            <td>
                                Dr. {{ $appt->doctor->first_name }} {{ $appt->doctor->last_name }}
                            </td>

                            <td>{{ $appt->department->name }}</td>

                            <td style="white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                            </td>

                            <td>{{ $appt->type }}</td>

                            <td>
                                <span class="badge badge-{{ $s }}">{{ $appt->status }}</span>
                            </td>

                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('admin.appointments.status', $appt) }}"
                                    style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <select
                                        name="status"
                                        style="
                                            padding:7px 10px;
                                            font-size:0.84rem;
                                            border:1.5px solid #d4e6da;
                                            border-radius:8px;
                                            outline:none;
                                            background:white;
                                        "
                                    >
                                        @foreach(['Pending','Confirmed','In Progress','Completed','Cancelled'] as $st)
                                            <option value="{{ $st }}" {{ $appt->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="btn btn-blue btn-sm">Save</button>
                                </form>
                            </td>

                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('admin.appointments.destroy', $appt) }}"
                                    onsubmit="return confirm('Delete this appointment permanently?')"
                                >
                                    @csrf
                                    @method('DELETE')
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

</div>

<style>
    .status-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 999px;
        background: #ffffff;
        border: 1.5px solid #d4e6da;
        color: #3a5a48;
        font-size: 0.9rem;
        font-weight: 700;
        transition: all 0.18s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .status-pill span {
        color: #6f8f7a;
        font-weight: 600;
    }

    .status-pill:hover {
        background: #eafaf1;
        border-color: #27ae60;
        color: #1a7a42;
    }

    .status-pill-active {
        background: linear-gradient(135deg, #27ae60, #1a7a42);
        color: white;
        border-color: #27ae60;
        box-shadow: 0 10px 20px rgba(39, 174, 96, 0.18);
    }

    .status-pill-active span {
        color: rgba(255,255,255,0.88);
    }

    @media (max-width: 768px) {
        .status-tabs {
            gap: 8px;
        }

        .status-pill {
            font-size: 0.84rem;
            padding: 9px 14px;
        }
    }
</style>

@endsection
