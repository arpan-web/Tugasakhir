<h2>Tambah Arsip Surat</h2>

<form action="/surat/store" method="POST" enctype="multipart/form-data">
@csrf

Nomor Surat <br>
<input type="text" name="nomor_surat"><br><br>

Judul Surat <br>
<input type="text" name="judul"><br><br>

Pengirim <br>
<input type="text" name="pengirim"><br><br>

Tanggal <br>
<input type="date" name="tanggal"><br><br>

Upload File <br>
<input type="file" name="file_surat"><br><br>

<button type="submit">Simpan</button>

</form>
