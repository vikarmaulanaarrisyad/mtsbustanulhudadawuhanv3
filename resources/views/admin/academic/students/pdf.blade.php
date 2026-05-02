<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa - {{ $setting->company_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
        }
        .title {
            text-align: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4f46e5;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $setting->company_name }}</h2>
        <p>{{ $setting->address }} | Telp: {{ $setting->phone }}</p>
    </div>

    <div class="title">
        <h3>DAFTAR DATA SISWA</h3>
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>NIS/NISN</th>
                <th>Nama Lengkap</th>
                <th>JK</th>
                <th>Kelas</th>
                <th>Tahun Akademik</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->nis }} / {{ $student->nisn }}</td>
                <td>{{ $student->nama_lengkap }}</td>
                <td>{{ $student->jenis_kelamin }}</td>
                <td>{{ $student->kelas_lengkap }}</td>
                <td>{{ $student->academicYear->academic_year ?? '-' }}</td>
                <td>{{ $student->studentStatus->student_status_name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sistem Informasi Manajemen Sekolah - {{ $setting->company_name }}</p>
    </div>
</body>
</html>
