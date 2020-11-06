@extends('layouts.app')

@section('content')

    <div class="container">
        <h1 class="page-header">Logs</h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Logs</a></li>
        </ol>
        @if(isset($arrLogs) && !is_null($arrLogs))
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Message</th>
                        <th>Data</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($arrLogs as $log)
                        <tr>
                            <th>{{ $log->id }}</th>
                            <td style="text-align: left">{{ $log->message }}</td>
                            <td style="text-align: left">{{ $log->create_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
