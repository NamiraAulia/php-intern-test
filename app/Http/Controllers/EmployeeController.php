<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    // Tampilkan daftar karyawan
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    // Tampilkan form tambah karyawan
    public function create()
    {
        return view('employees.create');
    }

    // Simpan karyawan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string|max:15|unique:employees,nomor',
            'nama' => 'required|string|max:150',
            'jabatan' => 'nullable|string|max:200',
            'talahir' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photo_upload_path = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->putFileAs('employee_photos', $file, $fileName, 'public');
            $photo_upload_path = Storage::disk('s3')->url($path);
        }

        $employee = Employee::create([
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'talahir' => $request->talahir,
            'photo_upload_path' => $photo_upload_path,
            'created_on' => now(),
            'created_by' => 'admin',
        ]);

        // Simpan ke Redis
        $this->cacheEmployee($employee);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    // Tampilkan detail karyawan
    public function show(Employee $employee)
    {
        // Coba ambil dari Redis dulu
        $cachedEmployee = $this->getCachedEmployee($employee->nomor);

        if ($cachedEmployee) {
            $employeeData = json_decode($cachedEmployee, true);
            $employee = new Employee($employeeData);
            $employee->exists = true;
            $employee->id = $employeeData['id'];
        }

        return view('employees.show', compact('employee'));
    }

    // Tampilkan form edit karyawan
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // Update karyawan
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string|max:15|unique:employees,nomor,' . $employee->id,
            'nama' => 'required|string|max:150',
            'jabatan' => 'nullable|string|max:200',
            'talahir' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photo_upload_path = $employee->photo_upload_path;

        if ($request->hasFile('photo')) {
            if ($employee->photo_upload_path) {
                $oldPath = str_replace(Storage::disk('s3')->url(''), '', $employee->photo_upload_path);
                Storage::disk('s3')->delete($oldPath);
            }

            $file = $request->file('photo');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->putFileAs('employee_photos', $file, $fileName, 'public');
            $photo_upload_path = Storage::disk('s3')->url($path);
        }

        $employee->update([
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'talahir' => $request->talahir,
            'photo_upload_path' => $photo_upload_path,
            'updated_on' => now(),
            'updated_by' => 'admin',
        ]);
        $this->cacheEmployee($employee);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {

        if ($employee->photo_upload_path) {
            $oldPath = str_replace(Storage::disk('s3')->url(''), '', $employee->photo_upload_path);
            Storage::disk('s3')->delete($oldPath);
        }

        $employee->delete();

        // Hapus dari Redis
        $this->forgetCachedEmployee($employee->nomor);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus!');
    }

    // --- Logika Redis Caching ---

    private function getRedisKey(string $nomor): string
    {
        return 'emp_' . $nomor;
    }

    private function cacheEmployee(Employee $employee): void
    {
        $key = $this->getRedisKey($employee->nomor);
        // Simpan seluruh record sebagai JSON string
        Cache::put($key, json_encode($employee->toArray()), now()->addMinutes(60)); // Cache selama 60 menit
    }

    private function getCachedEmployee(string $nomor): ?string
    {
        $key = $this->getRedisKey($nomor);
        return Cache::get($key);
    }

    private function forgetCachedEmployee(string $nomor): void
    {
        $key = $this->getRedisKey($nomor);
        Cache::forget($key);
    }
}
