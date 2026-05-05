@extends('layouts.app')
@section('title', 'Editar Usuario')

@section('content')
<div class="page-header">
    <h1>Editar Usuario — {{ $user->name }}</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Regresar</a>
</div>

<div class="card" style="max-width:520px">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="lbl">Nombre Completo *</label>
            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name', $user->name) }}" required/>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">Usuario</label>
            <input type="text" class="form-control" value="{{ $user->username }}" disabled
                   style="background:#f8fafc;color:#64748b"/>
            <small style="color:#94a3b8;font-size:11px">El nombre de usuario no puede modificarse.</small>
        </div>

        <div class="form-group">
            <label class="lbl">Correo Electrónico *</label>
            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email', $user->email) }}" required/>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">Rol / Departamento *</label>
            <select name="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" required>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role)===$role ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">Estado</label>
            <div class="form-check">
                <input type="checkbox" name="active" id="active" value="1"
                       {{ old('active', $user->active) ? 'checked' : '' }}
                       style="width:16px;height:16px;accent-color:#2563eb"/>
                <label for="active" style="font-size:14px;font-weight:normal;text-transform:none;letter-spacing:0">
                    Usuario activo
                </label>
            </div>
            <small style="color:#94a3b8;font-size:11px">Si se desactiva, el usuario no podrá iniciar sesión.</small>
        </div>

        <hr style="border:none;border-top:1px solid #e2e8f0;margin:18px 0"/>

        <div style="margin-bottom:6px;font-size:13px;font-weight:700;color:#475569">
            Cambiar contraseña (opcional)
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="lbl">Nueva Contraseña</label>
                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Dejar vacío para no cambiar"/>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="lbl">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repetir nueva contraseña"/>
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:6px">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
