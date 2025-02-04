@extends('template')

@section('title', 'presentaciones')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')

    @if (session('success'))
        <script>
            let msg = "{{ session('success') }}";
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: msg
            });
        </script>
    @endif


    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Presentaciones</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio </a></li>
            <li class="breadcrumb-item active">Presentaciones</li>
        </ol>

        <div class="mb-4">
            <a href="{{ route('presentaciones.create') }}">
                <button type="button" class="btn btn-primary">Añadir una nueva presentación</button>
            </a>
        </div>


        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                tabla presentaciones
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                        <tbody>
                            @foreach ($presentaciones as $presentacion)
                                <tr>
                                    <td>
                                        {{ $presentacion->caracteristicas->nombre }}
                                    </td>
                                    <td>
                                        {{ $presentacion->caracteristicas->descripcion }}
                                    </td>
                                    <td>
                                        @if ($presentacion->caracteristicas->estado == 1)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('presentaciones.edit', $presentacion->id) }}">
                                            <button type="button" class="btn btn-warning">Editar</button>
                                        </a>
    
                                        @if ($presentacion->caracteristicas->estado == 1)
                                            <button type="submit" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $presentacion->id }}">Eliminar</button>
                                        @else
                                            <button type="submit" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $presentacion->id }}">Restaurar</button>
                                        @endif
                                    </td>
                                </tr>
    
                                <!-- Modal -->
                                <div class="modal fade" id="confirmModal-{{ $presentacion->id }}" tabindex="-1"
                                    aria-labelledby="confirmModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="confirmModalLabel">Mensaje de confirmación</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{$presentacion->caracteristicas->estado == 1 ? '¿Estás seguro de que deseas eliminar esta presentación?' : '¿Estás seguro de que deseas restaurar esta presentación?'}}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
    
                                                <form action="{{ route('presentaciones.destroy', $presentacion->id) }}" method="post"
                                                    style="display: inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
