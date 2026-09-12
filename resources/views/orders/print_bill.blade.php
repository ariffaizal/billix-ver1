<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: monospace;
            font-size: 9px;
            /* text-align: center; */
        }

        h1,
        p {
            margin: 0;
        }

        .struk {
            width: 45mm;
            /* Sesuaikan dengan lebar struk Anda */
            margin: 0 auto;
            /* border: 1px solid black;
            padding: 10px; */
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
    $storeName = $data['store']->store_name ?? '';
    $storeDesc = $data['store']->store_desc ?? '';
    $storeAddress = $data['store']->store_address_1 ?? '';
@endphp

<body onload="window.print()">
    <div class="struk">
        <div class="header text-center">
           {{-- <img src="{{ asset('assets/logo.png') }}" alt="logo"
                style="height: 35px;filter: grayscale(100%) brightness(0%)"> --}}
			<img src="{{ asset('assets/logo.png') }}" alt="logo"
                style="height: 50px">
            {{-- <h1>{{ config('app.name') }}</h1> --}}
        </div>
        <table>
            <tr>
                <td class="text-bold">Receipt</td>
                <td class="text-bold text-end">Order#{{ $data['order']->id_order }}</td>
            </tr>
            <tr>
                <td class="text-bold">Date</td>
                <td class="text-bold text-end">{{ $data['order']->order_time }}</td>
            </tr>
            <tr>
                <td class="text-bold">Bill To</td>
                <td class="text-bold text-end">{{ $data['order']->bill_name }}</td>
            </tr>
        </table>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Items</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['items'] as $i)
                    <tr>
                        <td colspan="2" style="padding-bottom: 0">
                            {{ $i['items'] }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 0">
                            {{ $i['qty'] }} x {{ number_format($i['price'], 0, ',', '.') }}
                        </td>
                        <td class="text-end" style="padding-top: 0">
                            {{ number_format($i['amount'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        @if ($data['order']->order_status == 8)
            <h3 class="text-center">ORDER CANCELED</h3>
        @elseif ($data['order']->order_status == 7)
            <h3 class="text-center">ORDER REFUNDED</h3>
            <table>
                <tfoot>
                    <tr>
                        <td class="total">Sub Total</td>
                        <td class="total text-end">{{ number_format($data['order']->price_subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="total">Discount</td>
                        <td class="total text-end">{{ number_format($data['order']->price_discount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="total">VAT</td>
                        <td class="total text-end">{{ number_format($data['order']->price_vat, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="total">Total Refund</td>
                        <td class="total text-end">{{ number_format($data['order']->price_total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <table>
                <tfoot>
                    <tr>
                        <td class="total">Sub Total</td>
                        <td class="total text-end">{{ number_format($data['order']->price_subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="total">Discount</td>
                        <td class="total text-end">{{ number_format($data['order']->price_discount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="total">VAT</td>
                        <td class="total text-end">{{ number_format($data['order']->price_vat, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="total">Total</td>
                        <td class="total text-end">{{ number_format($data['order']->price_total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="total">Payment method</td>
                        <td class="total text-end">{{ $data['order']->pay_method }}</td>
                    </tr>
                    <tr>
                        <td class="total">Cash</td>
                        <td class="total text-end">{{ number_format($data['order']->cash_tendered, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="total">Change</td>
                        <td class="total text-end">{{ number_format($data['order']->cash_change, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif
        <hr>
        <p>By : {{ $data['user']->name }}</p>
        <br>
        <div class="text-center">
            <p>THANK YOU!</p>
            <p>Glad to see you again!</p>
        </div>
        <div class="showbutton">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Close</button>
        </div>
    </div>
</body>

</html>
