@extends('layouts.user.default')

@section('title')
    Tickets
@endsection

@section('breadcrumbTitle')
    <a href="{{ route('dashboardPage') }}">Dashboard</a> / Tickets
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h4 class="mt-50">Tickets List</h4>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('ticket.create') }}" class="btn btn-primary">Create Ticket</a>
        </div>
        <div class="col-xl-12 col-xxl-12">
            <div class="card mt-2">
                <div class="card-header">
                    <div></div>
                    <div>
                        <form id="noListform" method="GET" style="float:left;" class="me-50 form-dark">
                            <select class="form-control form-control-sm" name="noList" id="noList">
                                <option value="">No of Records</option>
                                <option value="30" {{ request()->get('noList') == '30' ? 'selected' : '' }}>30</option>
                                <option value="50" {{ request()->get('noList') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request()->get('noList') == '100' ? 'selected' : '' }}>100
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive custom-table">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date created</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($data) && $data->count())
                                    @foreach ($data as $ticket)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $ticket->title }}</td>
                                            <td>{{ Str::limit($ticket->body, 20) }}</td>
                                            <td>{{ convertDateToLocal($ticket->created_at, 'd-m-Y') }}</td>
                                            <td>
                                                @if ($ticket->status == '0')
                                                    <span class="badge badge-sm badge-danger">Pending</span>
                                                @elseif($ticket->status == '1')
                                                    <span class="badge badge-sm badge-warning">In Progress</span>
                                                @elseif($ticket->status == '3')
                                                    <span class="badge badge-sm badge-danger">Closed</span>
                                                @elseif($ticket->status == '2')
                                                    <span class="badge badge-sm badge-success">Reopened</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ticket->department == '1')
                                                    <span class="badge badge-sm badge-primary">Technical</span>
                                                @elseif($ticket->department == '2')
                                                    <span class="badge badge-sm badge-warning">Finance</span>
                                                @else
                                                    <span class="badge badge-sm badge-success">Customer Service</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                        data-bs-toggle="dropdown">
                                                        <svg width="5" height="17" viewBox="0 0 5 17" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2.36328 4.69507C1.25871 4.69507 0.363281 3.79964 0.363281 2.69507C0.363281 1.5905 1.25871 0.695068 2.36328 0.695068C3.46785 0.695068 4.36328 1.5905 4.36328 2.69507C4.36328 3.79964 3.46785 4.69507 2.36328 4.69507Z"
                                                                fill="#B3ADAD" />
                                                            <path
                                                                d="M2.36328 10.6951C1.25871 10.6951 0.363281 9.79964 0.363281 8.69507C0.363281 7.5905 1.25871 6.69507 2.36328 6.69507C3.46785 6.69507 4.36328 7.5905 4.36328 8.69507C4.36328 9.79964 3.46785 10.6951 2.36328 10.6951Z"
                                                                fill="#B3ADAD" />
                                                            <path
                                                                d="M2.36328 16.6951C1.25871 16.6951 0.363281 15.7996 0.363281 14.6951C0.363281 13.5905 1.25871 12.6951 2.36328 12.6951C3.46785 12.6951 4.36328 13.5905 4.36328 14.6951C4.36328 15.7996 3.46785 16.6951 2.36328 16.6951Z"
                                                                fill="#B3ADAD" />
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="{!! URL::route('ticket.show', [$ticket->id]) !!}" class="dropdown-item">Show</a>
                                                        @if ($ticket->status != '3')
                                                            <a href="{!! URL::route('ticket.close', [$ticket->id]) !!}"
                                                                class="dropdown-item">Close</a>
                                                        @else
                                                            <a href="{!! URL::route('ticket.reopen', [$ticket->id]) !!}"
                                                                class="dropdown-item">Reopen</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">
                                            <p class="text-center"><strong>No record found</strong></p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    @if (!empty($data) && $data->count())
                        <div class="row">
                            <div class="col-md-8">
                                {!! $data->appends($_GET)->links() !!}
                            </div>
                            <div class="col-md-4 text-right">
                                Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{ $data->total() }}
                                entries
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
