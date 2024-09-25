@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <h2>Médicos disponibles</h1>
                </div>
                <div class="col d-flex justify-content-end my-3">
                    <button class="btn btn-primary show-availability mx-1">Disponibilidad</button>
                    <a class="btn btn-primary" href="/report-schedules">Reporte horas</a>
                </div>
            </div>
        </div>
        <div class="container">
            <table id="employees-table" class="display">
                <thead>
                    <tr>
                        <th>Nombre(s)</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Ver horarios</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->first_name }}</td>
                            <td>{{ $employee->last_name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>
                                <button class="btn btn-info view-details" data-id="{{ $employee->id }}">Detalles</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#employees-table').DataTable();

            $('.view-details').on('click', function() {
                let id = $(this).data('id');
                let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                let url = `/show-schedules?id=${id}&timezone=${timezone}`;
                window.location.href = url;
            });

            $('.show-availability').on('click', function() {
                Swal.fire({
                    title: 'Seleccione un rango de tiempo',
                    html: `
                    <input type="date" id="start_date" class="swal2-input">
                    <input type="time" id="start_time" class="swal2-input">
                    <input type="date" id="end_date" class="swal2-input">
                    <input type="time" id="end_time" class="swal2-input">
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    preConfirm: () => {
                        let start_date = document.getElementById('start_date').value;
                        let start_time = document.getElementById('start_time').value;
                        let end_date = document.getElementById('end_date').value;
                        let end_time = document.getElementById('end_time').value;
                        if (!start_date || !start_time || !end_date || !end_time) {
                            Swal.showValidationMessage('Favor de rellenar todos los campos');
                        }
                        return {
                            start_date: start_date,
                            start_time: start_time,
                            end_date: end_date,
                            end_time: end_time
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let start_date = result.value.start_date;
                        let start_time = result.value.start_time;
                        let end_date = result.value.end_date;
                        let end_time = result.value.end_time;
                        let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                        let url =
                            `/show-availabity?start_date=${start_date}&start_time=${start_time}&end_date=${end_date}&end_time=${end_time}&timezone=${timezone}`;
                        window.location.href = url;
                    }
                });

            });
        });
    </script>
@endsection
