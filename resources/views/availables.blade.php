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
                    <th></th>
                </tr>
            </thead>
            <tbody>

                @foreach ($availableHours as $available)
                    <tr>
                        <td>{{ $available->date->format('Y-m-d') }}</td>
                        <td>{{ $available->employee->first_name }}</td>
                        <td>{{ $available->start_time_parsed . ' - ' . $available->end_time_parsed }}</td>
                        <td>{{ $available->local_start_time . ' - ' . $available->local_end_time }}</td>
                        <td>
                            <button type="button" class="btn btn-info take-hour"
                                data-id="{{ $available->id }}">Reservar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#available-hours-table').DataTable();

            $('.take-hour').on('click', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Confirmar',
                    text: "¿Deseas reservar este horario?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/api/create-appointment',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Swal.fire(
                                    '¡Reservación creada!',
                                    'Se ha recibido tu reservación.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Ocurrió un error',
                                    'error'
                                );
                            }
                        });
                    }
                });

            });
        });
    </script>
@endsection
