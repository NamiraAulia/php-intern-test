<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Detail Karyawan: {{ $employee->nama }}</h1>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary mb-3">Kembali ke Daftar Karyawan</a>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $employee->nama }}</h5>
                <p class="card-text"><strong>Nomor:</strong> {{ $employee->nomor }}</p>
                <p class="card-text"><strong>Jabatan:</strong> {{ $employee->jabatan ?? '-' }}</p>
                <p class="card-text"><strong>Tanggal Lahir:</strong> {{ $employee->talahir }}</p>
                <p class="card-text"><strong>Foto:</strong>
                    @if ($employee->photo_upload_path)
                        <img src="{{ $employee->photo_upload_path }}" alt="Foto Karyawan" width="150" class="img-thumbnail">
                    @else
                        Tidak ada foto
                    @endif
                </p>
                <p class="card-text"><small class="text-muted">Dibuat pada: {{ $employee->created_on }} oleh {{ $employee->created_by }}</small></p>
                @if ($employee->updated_on)
                    <p class="card-text"><small class="text-muted">Terakhir diupdate pada: {{ $employee->updated_on }} oleh {{ $employee->updated_by }}</small></p>
                @endif
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
