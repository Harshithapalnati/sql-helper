<!DOCTYPE html>
<html>
<head>
    <title>Database Tables</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg0: #070a12;
            --bg1: #0b1530;
            --surface: rgba(255, 255, 255, 0.08);
            --surface-strong: rgba(255, 255, 255, 0.12);
            --border: rgba(255, 255, 255, 0.14);
            --text: rgba(255, 255, 255, 0.92);
            --muted: rgba(255, 255, 255, 0.72);
            --accent: #7c3aed;
            --accent-2: #ec4899;
            --shadow: 0 18px 55px rgba(0, 0, 0, 0.45);
        }

        body {
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(980px 520px at 12% 12%, rgba(124, 58, 237, 0.42), transparent 60%),
                radial-gradient(880px 480px at 86% 18%, rgba(236, 72, 153, 0.28), transparent 55%),
                radial-gradient(900px 520px at 50% 105%, rgba(124, 58, 237, 0.18), transparent 60%),
                linear-gradient(180deg, var(--bg0), var(--bg1));
        }

        .page-shell {
            max-width: 1200px;
        }

        .card {
            background: linear-gradient(180deg, rgba(255,255,255,0.10), rgba(255,255,255,0.07));
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: var(--shadow);
            border-radius: 1rem;
        }

        h2, h4 { letter-spacing: 0.2px; }
        h4 {
            color: rgba(255, 255, 255, 0.92);
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 0.85rem;
        }
        h4::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.14);
        }

        .table {
            color: rgba(255, 255, 255, 0.88);
            border-color: rgba(255, 255, 255, 0.16) !important;
            background: rgba(10, 12, 20, 0.28);
        }
        .table th,
        .table td {
            color: rgba(255, 255, 255, 0.90) !important;
        }
        .table thead th {
            background: rgba(255, 255, 255, 0.07);
            color: rgba(255, 255, 255, 0.88);
            border-color: rgba(255, 255, 255, 0.16) !important;
        }
        .table tbody td {
            background: rgba(10, 12, 20, 0.18);
        }
        .table-bordered > :not(caption) > * {
            border-width: 1px;
            border-color: rgba(255, 255, 255, 0.16) !important;
        }

        .btn {
            border-radius: 0.85rem;
            transition: transform 140ms ease, filter 140ms ease, box-shadow 140ms ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0px); }

        .btn-outline-primary {
            color: var(--text) !important;
            border: 1px solid transparent !important;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.10), rgba(255, 255, 255, 0.06)) padding-box,
                linear-gradient(135deg, rgba(124, 58, 237, 0.65), rgba(236, 72, 153, 0.40)) border-box;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.22);
        }
        .btn-outline-primary:hover { filter: brightness(1.06); }
    </style>
</head>

<body>

<!-- Top-right nav -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1030;">
    <a href="{{ route('home') }}" class="btn btn-outline-primary">
        Back to Home
    </a>
</div>

<div class="container page-shell mt-5">

    <h2 class="mb-4 text-center">Database Preview</h2>

    <!-- PRODUCTS -->
    <div class="card mb-4 p-3 shadow-sm">
        <h4>Products</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @if(isset($products[0]))
                            @foreach($products[0] as $key => $value)
                                <th>{{ $key }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $row)
                        <tr>
                            @foreach($row as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ORDERS -->
    <div class="card mb-4 p-3 shadow-sm">
        <h4>Orders</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @if(isset($orders[0]))
                            @foreach($orders[0] as $key => $value)
                                <th>{{ $key }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $row)
                        <tr>
                            @foreach($row as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ORDER ITEMS -->
    <div class="card mb-4 p-3 shadow-sm">
        <h4>Order Items</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @if(isset($orderItems[0]))
                            @foreach($orderItems[0] as $key => $value)
                                <th>{{ $key }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $row)
                        <tr>
                            @foreach($row as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>