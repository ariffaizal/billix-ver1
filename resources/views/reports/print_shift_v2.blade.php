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
    use App\Models\OrderItems;
    use Illuminate\Support\Number;

    $openingCash = $data['shift']->initial_capital;
    // $orderPayCash = $data['shift']->orders->where('order_status', 9)->where('pay_method', 'Cash')->sum('price_total');
    // $orderPayTransfer = $data['shift']->orders
    //     ->where('order_status', 9)
    //     ->where('pay_method', 'Transfer')
    //     ->sum('price_total');
    // $orderPayQRIS = $data['shift']->orders->where('order_status', 9)->where('pay_method', 'QRIS')->sum('price_total');
    // $orderPayOther = $data['shift']->orders->where('order_status', 9)->where('pay_method', 'Other')->sum('price_total');

    // $orderRefund = $data['shift']->orders->where('order_status', 7)->sum('price_total');

    // $sumOrderPayment = $orderPayCash + $orderPayTransfer + $orderPayQRIS + $orderPayOther;
    // $totalAmount = $openingCash + $sumOrderPayment;
    // $netAmount = $totalAmount - $orderRefund;

    $orderPayCash = $data['shift']->orders->where('order_status', 9)->where('pay_method', 'Cash');
    $orderPayTransfer = $data['shift']->orders->where('order_status', 9)->where('pay_method', 'Transfer');
    $orderPayQRIS = $data['shift']->orders->where('order_status', 9)->where('pay_method', 'QRIS');
    $orderPayOther = $data['shift']->orders->where('order_status', 9)->where('pay_method', 'Other');
    $orderRefund = $data['shift']->orders->where('order_status', 7);

    // $sumOrderPayment = $orderPayCash + $orderPayTransfer + $orderPayQRIS + $orderPayOther;
    // $totalAmount = $openingCash + $sumOrderPayment;
    // $netAmount = $totalAmount - $orderRefund;

    $tableItemsCash = OrderItems::where('is_table', 1)
        ->whereIn('id_order', $orderPayCash->pluck('id_order'))
        ->sum('items_amount');

    $tableItemsTransfer = OrderItems::where('is_table', 1)
        ->whereIn('id_order', $orderPayTransfer->pluck('id_order'))
        ->sum('items_amount');

    $tableItemsQRIS = OrderItems::where('is_table', 1)
        ->whereIn('id_order', $orderPayQRIS->pluck('id_order'))
        ->sum('items_amount');

    $tableItemsOther = OrderItems::where('is_table', 1)
        ->whereIn('id_order', $orderPayOther->pluck('id_order'))
        ->sum('items_amount');

    $tableItemsTotal = $tableItemsCash + $tableItemsTransfer + $tableItemsQRIS + $tableItemsOther;

    $fnbItemsCash = OrderItems::where('is_fnb', 1)
        ->whereIn('id_order', $orderPayCash->pluck('id_order'))
        ->sum('items_amount');

    $fnbItemsTransfer = OrderItems::where('is_fnb', 1)
        ->whereIn('id_order', $orderPayTransfer->pluck('id_order'))
        ->sum('items_amount');

    $fnbItemsQRIS = OrderItems::where('is_fnb', 1)
        ->whereIn('id_order', $orderPayQRIS->pluck('id_order'))
        ->sum('items_amount');

    $fnbItemsOther = OrderItems::where('is_fnb', 1)
        ->whereIn('id_order', $orderPayOther->pluck('id_order'))
        ->sum('items_amount');

    $fnbItemsTotal = $fnbItemsCash + $fnbItemsTransfer + $fnbItemsQRIS + $fnbItemsOther;

    $totalTablesFnb = $tableItemsTotal + $fnbItemsTotal;

    $totalVat = $data['shift']->orders->where('order_status', 9)->sum('price_vat');

    $totalAmount = $totalTablesFnb + $totalVat + $openingCash;

    $totalDiscount = $data['shift']->orders->where('order_status', 9)->sum('price_discount');
    $totalRefund = $data['shift']->orders->where('order_status', 7)->sum('price_total');

    $netAmount = $totalAmount - $totalDiscount - $totalRefund;

    // Rekap total pendapatan FnB per kategori beserta detail item terjual
    $fnbByCategories = OrderItems::query()
        ->join('fnb_menus', 'order_items.id_fnb', '=', 'fnb_menus.id_fnb')
        ->join('fnb_category', 'fnb_menus.id_fnbcategory', '=', 'fnb_category.id_fnbcategory')
        ->where('order_items.is_fnb', 1)
        ->whereIn('order_items.id_order', $data['shift']->orders->where('order_status', 9)->pluck('id_order'))
        ->selectRaw('
            fnb_category.id_fnbcategory,
            fnb_category.category_name,
            order_items.fnb_name,
            order_items.fnb_price,
            SUM(order_items.fnb_qty) as total_qty,
            SUM(order_items.items_amount) as total_amount
        ')
        ->groupBy(
            'fnb_category.id_fnbcategory',
            'fnb_category.category_name',
            'order_items.fnb_name',
            'order_items.fnb_price'
        )
        ->get()
        ->groupBy('category_name');

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
                    <td class="text-bold">1. Tables</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Tables Cash</td>
                    <td class="text-end">{{ Number::format($tableItemsCash) }}</td>
                </tr>
                <tr>
                    <td>Tables Transfer</td>
                    <td class="text-end">{{ Number::format($tableItemsTransfer) }}</td>
                </tr>
                <tr>
                    <td>Tables QRIS</td>
                    <td class="text-end">{{ Number::format($tableItemsQRIS) }}</td>
                </tr>
                <tr>
                    <td>Tables Other</td>
                    <td class="text-end">{{ Number::format($tableItemsOther) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Total Tables</td>
                    <td class="text-bold text-end">{{ Number::format($tableItemsTotal) }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-bold">2. FnB</td>
                    <td></td>
                </tr>
                <tr>
                    <td>FnB Cash</td>
                    <td class="text-end">{{ Number::format($fnbItemsCash) }}</td>
                </tr>
                <tr>
                    <td>FnB Transfer</td>
                    <td class="text-end">{{ Number::format($fnbItemsTransfer) }}</td>
                </tr>
                <tr>
                    <td>FnB QRIS</td>
                    <td class="text-end">{{ Number::format($fnbItemsQRIS) }}</td>
                </tr>
                <tr>
                    <td>FnB Other</td>
                    <td class="text-end">{{ Number::format($fnbItemsOther) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Total FnB</td>
                    <td class="text-bold text-end">{{ Number::format($fnbItemsTotal) }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-bold">Total Tables + FnB</td>
                    <td class="text-bold text-end">{{ Number::format($totalTablesFnb) }}</td>
                </tr>
                <tr>
                    <td>VAT</td>
                    <td class="text-end">{{ Number::format($totalVat) }}</td>
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
                    <td>Discount</td>
                    <td class="text-end">{{ Number::format($totalDiscount) }}</td>
                </tr>
                <tr>
                    <td>Refund</td>
                    <td class="text-end">{{ Number::format($totalRefund) }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Net Amount</td>
                    <td class="text-bold text-end">{{ Number::format($netAmount) }}</td>
                </tr>
                <!-- FnB Sales Breakdown by Category & Items -->
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-bold" colspan="2">3. FnB Sales by Category & Items</td>
                </tr>
                @forelse($fnbByCategories as $categoryName => $items)
                    <tr>
                        <td class="text-bold" style="padding-left: 5px;" colspan="2">
                            [{{ $categoryName }}]
                        </td>
                    </tr>
                    @foreach($items as $item)
                        <tr>
                            <td style="padding-left: 15px;">
                                {{ $item->fnb_name }}<br>
                                <small style="color: #555;">{{ $item->total_qty }} x {{ Number::format($item->fnb_price) }}</small>
                            </td>
                            <td class="text-end" style="vertical-align: bottom;">
                                {{ Number::format($item->total_amount) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-bold" style="padding-left: 15px;">Subtotal {{ $categoryName }}</td>
                        <td class="text-bold text-end">{{ Number::format($items->sum('total_amount')) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td style="padding-left: 10px;" colspan="2"><em>Tidak ada penjualan FnB</em></td>
                    </tr>
                @endforelse
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
