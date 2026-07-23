<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            font-size: 10px;
        }

        th {
            background: #dddddd;
        }

        .header-table {
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .kop-instansi {
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
        }

        .kop-pemerintah {
            font-size: 15px;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .kop-dinas {
            font-size: 21px;
            font-weight: bold;
            margin: 2px 0 0 0;
            text-transform: uppercase;
        }

        .kop-alamat {
            font-size: 11px;
            margin: 4px 0 0 0;
        }

        .kop-kontak {
            font-size: 11px;
            margin: 1px 0 0 0;
        }

        .title {
            text-align: center;
            margin: 16px 0;
        }

        .meta {
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('img/logo kota kediri.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
    @endphp

    <table width="100%" class="header-table">
        <tr>
            <td width="15%" style="text-align:center;">
                @if ($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" width="80" alt="Logo">
                @else
                    <span style="font-size:10px;">Logo</span>
                @endif
            </td>
            <td class="kop-instansi">
                <p class="kop-pemerintah">PEMERINTAH KOTA KEDIRI</p>
                <p class="kop-dinas">DINAS KETAHANAN PANGAN DAN PERTANIAN</p>
                <p class="kop-alamat">Jl. Brigjend. Pol. Imam Bachri H.P No.98A Kota Kediri</p>
                <p class="kop-kontak">surel : dkppkotakediri@kedirikota.go.id &nbsp;&nbsp;laman : dkpp.kedirikota.go.id</p>
            </td>
        </tr>
    </table>

    <div style="border-top:3px solid black; margin-top:8px;"></div>
    <div style="border-top:1px solid black; margin-top:1px;"></div>

    <h2 class="title">LAPORAN REKAP MEDIS</h2>

    <table width="100%" style="border:none; margin-bottom:14px;">
        <tr>
            <td style="border:none; width:55%; vertical-align:top; font-size:11px;">
                <strong>Filter yang diterapkan:</strong><br>
                @forelse($filterInfo as $label => $value)
                    {{ $label }} : {{ $value }}<br>
                @empty
                    Tidak ada filter (menampilkan seluruh data)<br>
                @endforelse
            </td>
            <td style="border:none; width:45%; vertical-align:top; text-align:right; font-size:11px;">
                <strong>Ringkasan:</strong><br>
                Total Entri&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $totalEntri }}<br>
                Total Hewan (Unik) : {{ $totalHewanUnik }}<br>
                Total Retribusi&nbsp;&nbsp;: Rp {{ number_format($totalRetribusi, 0, ',', '.') }}<br>
                Tanggal Cetak&nbsp;&nbsp;&nbsp;&nbsp;: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pemilik</th>
                <th>Alamat</th>
                <th>Nama Hewan</th>
                <th>Jenis Hewan</th>
                <th>Kelamin</th>
                <th>Anamnesa</th>
                <th>Diagnosa</th>
                <th>Pelayanan</th>
                <th>Dokter</th>
                <th>Paramedis</th>
                <th>Terapi</th>
                <th>No. Karcis</th>
                <th>Retribusi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $index => $item)
                @php
                    $anamnesaList = $item->anamnesas->pluck('nama_anamnesa')->implode(', ');
                    $obatList = $item->obats->pluck('nama_obat')->implode(', ');
                    $tanggal = \Carbon\Carbon::parse($item->tanggal);
                    if ($tanggal->format('H:i:s') === '00:00:00' && $item->created_at) {
                        $tanggal = $tanggal->setTime($item->created_at->hour, $item->created_at->minute, $item->created_at->second);
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tanggal->translatedFormat('d/m/Y H:i:s') }}</td>
                    <td>{{ $item->hewan?->pemilik?->nama_pemilik ?? '-' }}</td>
                    <td>{{ $item->hewan?->pemilik?->alamat ?? '-' }}</td>
                    <td>{{ $item->hewan?->nama_hewan ?? '-' }}</td>
                    <td>{{ $item->hewan?->jenisHewan?->nama_jenis ?? '-' }}</td>
                    <td>{{ $item->hewan?->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $anamnesaList ?: '-' }}</td>
                    <td>{{ $item->diagnosa?->nama_diagnosa ?? '-' }}</td>
                    <td>{{ $item->pelayanan?->nama_pelayanan ?? '-' }}</td>
                    <td>{{ $item->dokter?->nama_dokter ?? '-' }}</td>
                    <td>{{ $item->paramedis?->nama_paramedis ?? '-' }}</td>
                    <td>{{ $obatList ?: '-' }}</td>
                    <td>{{ $item->no_karcis ?? '-' }}</td>
                    <td>Rp {{ number_format($item->pelayanan?->tarif ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script type="text/php">
    if (isset($pdf)) {
        $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
        $font = $fontMetrics->getFont("Times New Roman", "italic");
        $size = 8;
        $width = $fontMetrics->get_text_width($text, $font, $size);
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 25;
        $pdf->page_text($x, $y, $text, $font, $size, [0, 0, 0]);
    }
    </script>
</body>
</html>
