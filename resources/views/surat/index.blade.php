<!DOCTYPE html>
<html>
<head>
<title>Arsip Surat</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="container">
<h2>Data Arsip Surat</h2>

<a href="/surat/create">Tambah Surat</a>

<table border="1">
<tr>
<th>No</th>
<th>Nomor Surat</th>
<th>Judul</th>
<th>Pengirim</th>
<th>Tanggal</th>
<th>File</th>
<th>Aksi</th>
</tr>

@foreach($surat as $s)
<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $s->nomor_surat }}</td>
<td>{{ $s->judul }}</td>
<td>{{ $s->pengirim }}</td>
<td>{{ $s->tanggal }}</td>
<td>
<a href="/surat/{{ $s->file_surat }}">Download</a>
</td>
<td>
<form action="/surat/{{ $s->id }}" method="POST">
@csrf
@method('DELETE')
<button type="submit">Hapus</button>
</form>
</td>
</tr>
@endforeach

</table>
