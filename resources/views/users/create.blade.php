@extends('layouts.app')
@section('title', 'Nuevo Usuario')

@section('content')
<div class="page-header">
    <h1>Nuevo Usuario</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Regresar</a>
</div>

<div class="card" style="max-width:520px">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-group">
            <label class="lbl">Nombre Completo *</label>
            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name') }}" placeholder="Nombre Apellido" required/>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="lbl">Usuario *</label>
                <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                       value="{{ old('username') }}" placeholder="usuario123" required/>
                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="lbl">Correo Electrónico *</label>
                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}" placeholder="correo@halcon.com" required/>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="lbl">Contraseña *</label>
                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Mínimo 6 caracteres" required/>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="lbl">Confirmar Contraseña *</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repetir contraseña" required/>
            </div>
        </div>

        <div class="form-group">
            <label class="lbl">Rol / Departamento *</label>
            <select name="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" required>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role')===$role ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:10px;margin-top:6px">
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
