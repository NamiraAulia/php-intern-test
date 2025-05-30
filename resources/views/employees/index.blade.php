<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Daftar Karyawan</h1>
        <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Tambah Karyawan Baru</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Tanggal Lahir</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $employee->nomor }}</td>
                        <td>{{ $employee->nama }}</td>
                        <td>{{ $employee->jabatan }}</td>
                        <td>{{ $employee->talahir }}</td>
                        <td>
                            @if ($employee->photo_upload_path)
                                <img src="{{ $employee->photo_upload_path }}" alt="Foto Karyawan" width="50">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
