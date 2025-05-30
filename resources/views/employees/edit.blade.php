<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Karyawan: {{ $employee->nama }}</h1>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary mb-3">Kembali ke Daftar Karyawan</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 

            <div class="mb-3">
                <label for="nomor" class="form-label">Nomor Karyawan</label>
                <input type="text" class="form-control" id="nomor" name="nomor" value="{{ old('nomor', $employee->nomor) }}" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Karyawan</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $employee->nama) }}" required>
            </div>
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $employee->jabatan) }}">
            </div>
            <div class="mb-3">
                <label for="talahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="talahir" name="talahir" value="{{ old('talahir', date('Y-m-d', strtotime($employee->talahir))) }}">
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Upload Foto Baru</label>
                <input type="file" class="form-control" id="photo" name="photo">
                @if ($employee->photo_upload_path)
                    <small class="form-text text-muted">Foto saat ini:</small><br>
                    <img src="{{ $employee->photo_upload_path }}" alt="Foto Karyawan" width="100" class="mt-2">
                @else
                    <small class="form-text text-muted">Belum ada foto.</small>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Update Karyawan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
