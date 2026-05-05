@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
<div class="page-header">
    <h1>Usuarios</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">➕ Nuevo Usuario</a>
</div>

<table>
    <thead>
        <tr><th>Nombre</th><th>Usuario</th><th>Email</th><th>Rol / Depto.</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr style="{{ !$user->active ? 'opacity:.6' : '' }}">
            <td><strong>{{ $user->name }}</strong></td>
            <td style="font-family:monospace;font-size:13px">{{ $user->username }}</td>
            <td style="font-size:13px">{{ $user->email }}</td>
            <td><span class="badge" style="background:#e2e8f0;color:#475569">{{ $user->role_label }}</span></td>
            <td>
                @if($user->active)
                    <span class="badge badge-active">● Activo</span>
                @else
                    <span class="badge badge-inactive">○ Inactivo</span>
                @endif
            </td>
            <td>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#64748b;padding:24px">Sin usuarios registrados.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
