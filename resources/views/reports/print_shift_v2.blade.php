<!DOCTYPE html>
<html>

<head>
    <title>Laporan {{ $data['shift']->id_user_shift }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }

        h1,
        p {
            margin: 0;
        }

        .struk {
            width: 65mm;
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
            body {
                font-size: 9px;
            }

            .showbutton {
                margin-top: 0;
                display: none;
            }

            .struk {
                width: 45mm;
                margin: 0;
            }
        }
    </style>
</head>

@php
    use Illuminate\Support\Number;

    $summary = $data['summary'];
    $openingCash = $summary['opening_cash'];
    $tableItemsCash = $summary['cash'];
    $tableItemsTransfer = $summary['transfer'];
    $tableItemsQRIS = $summary['qris'];
    $tableItemsOther = $summary['other'];
    $tableItemsTotal = $summary['cash'] + $summary['transfer'] + $summary['qris'] + $summary['other'];
    $totalAmount = $summary['total'];
    $totalRefund = $summary['refund'];
    $netAmount = $summary['net'];
    $expectedCash = $summary['expected_cash'];
    $diff = $summary['diff'];
@endphp

<body onload="">
    <div class="struk">
        <div class="header text-center">
            {{-- <img src="{{ asset('assets/logo_bg_no.png') }}" alt="logo"
                style="height: 35px;filter: grayscale(100%) brightness(0%)"> --}}
            <h1>{{ config('app.name') }}</h1>
        </div>
        <table>
            <tr>
                <td class="text-bold">Shift No</td>
                <td class="text-bold text-end">{{ $data['shift']->id_user_shift }}</td>
            </tr>
            <tr>
                <td class="text-bold">Operator</td>
                <td class="text-bold text-end">{{ $data['shift']->shift_info }}</td>
            </tr>
            <tr>
                <td class="text-bold">Opening Time</td>
                <td class="text-bold text-end">{{ $data['shift']->shift_start }}</td>
            </tr>
            <tr>
                <td class="text-bold">Closing Time</td>
                <td class="text-bold text-end">{{ $data['shift']->shift_end }}</td>
            </tr>
        </table>
        <hr>
        <p class="text-center text-bold">SUMMARY REPORT</p>
        <hr>
        <table>
            <tbody>

                <tr>
                    <td class="text-bold">1. Payment Method</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    <td class="text-end">{{ Number::format($summary['cash']) }}</td>
                </tr>
                <tr>
                    <td>Transfer</td>
                    <td class="text-end">{{ Number::format($summary['transfer']) }}</td>
                </tr>
                <tr>
                    <td>QRIS</td>
                    <td class="text-end">{{ Number::format($summary['qris']) }}</td>
                </tr>
                <tr>
                    <td>Other</td>
                    <td class="text-end">{{ Number::format($summary['other']) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Total Payment</td>
                    <td class="text-bold text-end">{{ Number::format($tableItemsTotal) }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Opening Cash</td>
                    <td class="text-end">{{ Number::format($openingCash) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Total Amount</td>
                    <td class="text-bold text-end">{{ Number::format($totalAmount) }}</td>
                </tr>
                <tr>
                    <td>Refund</td>
                    <td class="text-end">{{ Number::format($totalRefund) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Net Amount</td>
                    <td class="text-bold text-end">{{ Number::format($netAmount) }}</td>
                </tr>
                <tr>
                    <td>Expected Cash</td>
                    <td class="text-end">{{ Number::format($expectedCash) }}</td>
                </tr>
                <tr>
                    <td>Difference</td>
                    <td class="text-end">{{ Number::format($diff) }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <p>Printed By : {{ auth()->user()->name }}</p>
        <br>
        <div class="showbutton">
            <button onclick="window.print()" style="padding: 10px 15px;">Print</button>
            <button onclick="self.close()" style="padding: 10px 15px;">Close</button>
        </div>
    </div>
</body>

</html>
