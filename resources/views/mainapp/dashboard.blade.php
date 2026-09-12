@extends('layouts.main')

@section('title', $title)

@section('content')

    {{-- Row Revenue --}}
    @if (auth()->user()->role == 'owner')
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-end px-3 py-2">
                        <span class="fs-2 font-bold text-success">
                            {{ number_format($data['revenueToday'], 0, ',', '.') }}</span>
                        <h6 class="m-0 p-0">Today's</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-end px-3 py-2">
                        <span class="fs-2 font-bold text-danger">
                            {{ number_format($data['refundToday'], 0, ',', '.') }}</span>
                        <h6 class="m-0 p-0">Refund</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-end px-3 py-2">
                        <span class="fs-2 font-bold text-primary">
                            {{ number_format($data['revenueLast7Days'], 0, ',', '.') }}</span>
                        <h6 class="m-0 p-0">Last 7 days</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-end px-3 py-2">
                        <span class="fs-2 font-bold text-warning">
                            {{ number_format($data['revenueThisMonth'], 0, ',', '.') }}</span>
                        <h6 class="m-0 p-0">This month</h6>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- End Row Revenue --}}
    {{-- Row Order --}}
    <div class="row mb-3">
        <div class="col-12">
            <h4>Orders</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-end px-3 py-2">
                    <button type="button" class="btn m-0 p-0" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                        <span class="fs-2 font-bold">{{ $data['odraft'] }}</span>
                        <h6 class="text-white m-0 p-0">New Order</h6>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-end px-3 py-2">
                    <span class="fs-2 font-bold">{{ $data['ototal'] }}</span>
                    <h6 class="text-white m-0 p-0">Order Total</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-end px-3 py-2">
                    <a href="{{ url('orders/pending') }}">
                        <span class="fs-2 font-bold">{{ $data['opending'] }}</span>
                        <h6 class="text-white m-0 p-0">Pending Payment</h6>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-end px-3 py-2">
                    <span class="fs-2 font-bold">{{ $data['opstart'] }}</span>
                    <h6 class="text-white m-0 p-0">Pending Start</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-end px-3 py-2">
                    <span class="fs-2 font-bold">{{ $data['ofinish'] }}</span>
                    <h6 class="text-white m-0 p-0">Active & Finish</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-end px-3 py-2">
                    <span class="fs-2 font-bold" id="cekavail"></span>
                    <h6 class="text-white m-0 p-0">Table Available</h6>
                </div>
            </div>
        </div>
    </div>
    {{-- End Row Order --}}
    {{-- Row Table --}}
    <div class="row mb-3">
        <div class="col-12">
            <h4>All Table</h4>
            <p class="mb-0">Refreshed every 5 second</p>
        </div>
    </div>
    <div class="row" id="showAllTable"></div>
    {{-- End Row Table --}}
    {{-- Modal --}}
    <div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newOrderLabel">new Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 border-bottom">
                        <form action="{{ route('orders.create') }}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="open_bill">
                            <button type="submit" class="btn btn-success">Open Billing</button>
                        </form>
                        <i>Tidak ada batasan waktu, harga sewa dihitung per menit</i>
                    </div>
                    <div class="mb-3 border-bottom">
                        <form action="{{ route('orders.create') }}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="packages">
                            <button type="submit" class="btn btn-primary">Packages</button>
                        </form>
                        <i>harga sewa berdasarkan waktu</i>
                    </div>
                    <div class="mb-3 border-bottom">
                        <form action="{{ route('orders.create') }}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="fnb_only">
                            <button type="submit" class="btn btn-danger">FnB Only</button>
                        </form>
                        <i>Hanya untuk memesan makanan/minuman</i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        fetchData();
        async function fetchData() {
            try {
                let countB = 0;
                const response = await fetch('/settings/table/showallstatus');
                const data = await response.json();

                const divCekBoard = document.getElementById('showAllTable');
                const divBoardAvail = document.getElementById('cekavail');
                divCekBoard.innerHTML = ''; // Clear previous content

                data.forEach(table => {
                    let showTime = '';
                    let cardBg = '';
                    let cardStyle = '';
                    let linkDetail = 'null';
                    let textStatus = '';
                    if (table.id_table_active !== null) {
                        cardBg = 'bg-success';
                        cardStyle = 'style="cursor:pointer"';
                        textStatus = 'Active Session';
                        if (table.table_session_status == 1) {
                            if (table.is_openbill == 1) {
                                showTime = hitungDurasi(table.time_start);
                                linkDetail = '/orders/openbill/' + table.id_order;
                            } else {
                                linkDetail = '/orders/view/' + table.id_order;
                                showTime = hitungSisaWaktu(table.time_now, table.time_end)
                            }
                        }
                    } else if (table.table_can_use == 0) {
                        cardBg = 'bg-secondary'
                        textStatus = 'Inactive';
                    } else {
                        cardBg = 'bg-info';
                        textStatus = 'Available';
                        countB++;
                    }

                    const boardElement = document.createElement('div');
                    boardElement.classList.add('col-sm-4', 'col-lg-2');
                    boardElement.innerHTML = `
                            <div class="card ${cardBg} text-white vieworder" ${cardStyle} data-link="${linkDetail}"">
                                <div class="card-body p-2">
                                    <p class="m-0">${table.table_name}</p>
                                    <p class="m-0">
                                        ${textStatus}
                                    </p>
                                        ${showTime} 
                                </div>
                            </div>
                    `;
                    divCekBoard.appendChild(boardElement);
                });

                divBoardAvail.innerHTML = countB;

            } catch (error) {
                console.error('Terjadi kesalahan:', error);
                // Tampilkan pesan error jika terjadi kesalahan
                const divCekBoard = document.getElementById('showAllTable');
                divCekBoard.innerHTML = 'Terjadi kesalahan saat mengambil data.';
            }
        }
        setInterval(fetchData, 5000);

        function hitungSisaWaktu(timeStart, timeEnd) {
            // Mengubah string waktu menjadi objek Date
            const startDate = new Date(timeStart);
            const endDate = new Date(timeEnd);

            // Menghitung selisih waktu dalam milidetik
            const selisihWaktuMilidetik = endDate - startDate;

            // Mengubah selisih waktu menjadi detik, menit, dan jam
            const totalDetik = Math.floor(selisihWaktuMilidetik / 1000);
            const jam = Math.floor(totalDetik / 3600);
            const menit = Math.floor((totalDetik % 3600) / 60);
            const detik = totalDetik % 60;

            const showJam = checkTime(jam);
            const showMenit = checkTime(menit);
            const showDetik = checkTime(detik);

            // Memformat hasil
            const hasil = `${showJam}:${showMenit}:${showDetik}`;
            if (jam < 0 || menit < 0 || detik < 0) {
                return '00:00:00';
            } else {
                return hasil;
            }
        }


        function hitungDurasi(timeStart) {
            // Mengubah string waktu menjadi objek Date
            const startDate = new Date(timeStart);

            // Validasi input
            if (isNaN(startDate.getTime())) {
                return 'Tanggal awal tidak valid';
            }

            const now = new Date();
            const selisihWaktuMilidetik = now - startDate;

            // Menghitung selisih waktu dalam detik, menit, dan jam
            const totalDetik = Math.floor(selisihWaktuMilidetik / 1000);
            const jam = Math.floor(totalDetik / 3600);
            const menit = Math.floor((totalDetik % 3600) / 60);
            const detik = totalDetik % 60;

            // Format hasil
            const showJam = checkTime(jam);
            const showMenit = checkTime(menit);
            const showDetik = checkTime(detik);

            return `${showJam}:${showMenit}:${showDetik}`;
        }

        function checkTime(i) {
            return i.toString().padStart(2, '0');
        }

        $("#showAllTable").on("click", ".vieworder", function() {
            let dataLink = $(this).attr("data-link");
            if (dataLink != 'null') {
                window.location.href = dataLink;
            }
        })
    </script>
@endsection
