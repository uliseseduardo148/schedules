@extends('layouts.main')

@section('content')
<div class="container">
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <h2>Reporte de horarios</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <table id="employees-table" class="display">
            <thead>
                <tr>
                    <th>Nombre(s)</th>
                    <th>Apellidos</th>
                    <th>Horas disponibles</th>
                    <th>Horas reservadas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->first_name }}</td>
                        <td>{{ $employee->last_name }}</td>
                        <td>{{ $employee->workSchedules->sum('total_active_hours') ?? 0 }}</td>
                        <td>{{ $employee->workSchedules->sum('total_inactive_hours') ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#employees-table').DataTable({
            dom: 'Bfrtip', // Defines the placement of buttons
                buttons: [
                    'csv',  // Export to CSV
                    'excel', // Export to Excel
                    'pdf',  // Export to PDF
                    'print' // Print view
                ]
        });
    });
</script>
@endsection