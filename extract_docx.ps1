Add-Type -AssemblyName System.IO.Compression.FileSystem

$docxPath = 'D:\IPAN\SEMESTER 6\TUGAS AKHIR\TUGAS AKHIR\Draft Template Penulisan Laporan Tugas Akhir (TA) 2025.docx'
$zip = [System.IO.Compression.ZipFile]::OpenRead($docxPath)
$entry = $zip.Entries | Where-Object { $_.FullName -eq 'word/document.xml' }

$stream = $entry.Open()
$reader = New-Object System.IO.StreamReader($stream)
$xmlContent = $reader.ReadToEnd()
$reader.Close()
$stream.Close()
$zip.Dispose()

[xml]$xml = $xmlContent
$ns = New-Object System.Xml.XmlNamespaceManager($xml.NameTable)
$ns.AddNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main')

$pNodes = $xml.SelectNodes('//w:p', $ns)
$lines = [System.Collections.Generic.List[string]]::new()
foreach ($p in $pNodes) {
    $tNodes = $p.SelectNodes('.//w:t', $ns)
    if ($tNodes) {
        $text = ($tNodes | ForEach-Object { $_.'#text' }) -join ''
        if (-not [string]::IsNullOrWhiteSpace($text)) {
            $lines.Add($text)
        }
    }
}

$lines | Out-File -FilePath 'extracted_doc.txt' -Encoding utf8
Write-Output "Done: $($lines.Count) lines extracted."
