@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Horarios disponibles</h1>
        <table id="available-hours-table" class="display">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Médico</th>
                    <th>Horario del médico</th>
                    <th>Horario local</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($availableHours as $available)
                    <tr>
                        <td>{{ $available->date->format('Y-m-d') }}</td>
                        <td>{{ $available->employee->first_name}}</td>
                        <td>{{ $available->start_time_parsed . ' - ' . $available->end_time_parsed }}</td>
                        <td>{{ $available->local_start_time . ' - ' . $available->local_end_time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#available-hours-table').DataTable();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endsection
