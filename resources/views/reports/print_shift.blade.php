<!DOCTYPE html>
<html>

<head>
    <title>Laporan #22</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 9px;
        }

        h1,
        p {
            margin: 0;
        }

        .struk {
            width: 45mm;
            margin: 0 auto;
        }

        .header {
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        hr {
            border-top: 1px dashed #000;
        }


        .total {
            font-weight: bold;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: end;
        }

        .showbutton {
            margin-top: 30px;
        }

        .m-0 {
            margin: 0;
        }

        @media print {
            .showbutton {
                margin-top: 0;
                display: none;
            }

            .struk {
                margin: 0;
            }
        }
    </style>
</head>

@php
    use Illuminate\Support\Number;

    // LACI UANG
    $modalAwal = !empty($data['shift']->initial_capital) ? $data['shift']->initial_capital : 0;
    $bayarTunai = !empty($data['sumCash']->total_price) ? $data['sumCash']->total_price : 0;
    $pengeluaran = !empty($data['shift']->cash_out) ? $data['shift']->cash_out : 0;
    $cashYgDiharapkan = $modalAwal + $bayarTunai - $pengeluaran;
    $cashSebenarnya = !empty($data['shift']->cash_actual) ? $data['shift']->cash_actual : 0;
    $cashSelisih = $cashSebenarnya - $cashYgDiharapkan;

    // PENJUALAN
    $totalDiskon = !empty($data['sum']->total_discount) ? $data['sum']->total_discount : 0;
    $totalPajak = !empty($data['sum']->total_vat) ? $data['sum']->total_vat : 0;
    $totalRefund = !empty($data['sumRefund']) ? $data['sumRefund'] : 0;
    $totalKotor = !empty($data['sum']->total_price) ? $data['sum']->total_price + $totalRefund : 0;
    $totalBersih = $totalKotor - $totalDiskon - $totalRefund;
    $totalPendapatan = $totalBersih - $totalPajak;
@endphp

<body onload="">
    <div class="struk">
        <div class="header text-center">
 			{{-- <img src="{{ asset('assets/logo.png') }}" alt="logo"
                style="height: 35px;filter: grayscale(100%) brightness(0%)"> --}}
			<img src="{{ asset('assets/logo.png') }}" alt="logo"
                style="height: 50px;">
            {{-- <h1>{{ config('app.name') }}</h1> --}}

        </div>
        <table>
            <tr>
                <td class="text-bold">Shift No</td>
                <td class="text-bold text-end">{{ $data['shift']->id_user_shift }}</td>
            </tr>
            <tr>
                <td class="text-bold">Shift Open</td>
                <td class="text-bold text-end">{{ $data['shift']->shift_start }}</td>
            </tr>
            <tr>
                <td class="text-bold">Shift Close</td>
                <td class="text-bold text-end">{{ $data['shift']->shift_end }}</td>
            </tr>
        </table>
        <hr>
        <p class="text-center">LACI UANG</p>
        <hr>
        <table>
            <tbody>
                <tr>
                    <td>Modal Awal</td>
                    <td class="text-end">{{ Number::format($modalAwal) }}</td>
                </tr>
                <tr>
                    <td>Pembayaran Tunai</td>
                    <td class="text-end">{{ Number::format($bayarTunai) }}</td>
                </tr>
                <tr>
                    <td>Pengeluaran</td>
                    <td class="text-end">{{ Number::format($pengeluaran) }}</td>
                </tr>
                <tr>
                    <td>Jumlah Uang Tunai yang diharapkan</td>
                    <td class="text-end">{{ Number::format($cashYgDiharapkan) }}</td>
                </tr>
                <tr>
                    <td>Jumlah Uang Tunai sebenarnya</td>
                    <td class="text-end">{{ Number::format($cashSebenarnya) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Selisih</td>
                    <td class="text-bold text-end">{{ Number::format($cashSelisih) }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <p class="text-center">RINGKASAN PENJUALAN</p>
        <hr>
        <table>
            <tbody>
                <tr>
                    <td class="text-bold">Penjualan Kotor</td>
                    <td class="text-bold text-end">{{ Number::format($totalKotor) }}</td>
                </tr>
                <tr>
                    <td>Diskon</td>
                    <td class="text-end">{{ Number::format($totalDiskon) }}</td>
                </tr>
                <tr>
                    <td>Pengembalian</td>
                    <td class="text-end">{{ Number::format($totalRefund) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Penjualan Bersih</td>
                    <td class="text-bold text-end">{{ Number::format($totalBersih) }}</td>
                </tr>
                <tr>
                    <td>Pajak</td>
                    <td class="text-end">{{ Number::format($totalPajak) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Total Pendapatan</td>
                    <td class="text-bold text-end">{{ Number::format($totalPendapatan) }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <p>Printed By : {{ auth()->user()->name }}</p>
        <br>
        <div class="showbutton">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Close</button>
        </div>
    </div>
</body>

</html>
