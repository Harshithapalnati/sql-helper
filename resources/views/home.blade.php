<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Helper – Internal Analytics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg0: #070a12;
            --bg1: #0b1530;
            --surface: rgba(255, 255, 255, 0.08);
            --surface-strong: rgba(255, 255, 255, 0.12);
            --border: rgba(255, 255, 255, 0.14);
            --text: rgba(255, 255, 255, 0.92);
            --muted: rgba(255, 255, 255, 0.72);
            --accent: #7c3aed;       /* purple */
            --accent-2: #ec4899;     /* pink */
            --danger: #fb7185;
            --shadow: 0 18px 55px rgba(0, 0, 0, 0.45);
        }

        /* Layout */
        body {
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(980px 520px at 12% 12%, rgba(124, 58, 237, 0.42), transparent 60%),
                radial-gradient(880px 480px at 86% 18%, rgba(236, 72, 153, 0.28), transparent 55%),
                radial-gradient(900px 520px at 50% 105%, rgba(124, 58, 237, 0.18), transparent 60%),
                linear-gradient(180deg, var(--bg0), var(--bg1));
        }

        .app-container {
            width: 100%;
            max-width: 1100px;
            margin: 40px auto;
            background: linear-gradient(180deg, rgba(255,255,255,0.10), rgba(255,255,255,0.07));
            padding: 2.5rem;
            border-radius: 1.25rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(10px);
        }

        h2 {
            letter-spacing: 0.2px;
        }

        /* Components */
        .form-control {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid var(--border);
            color: var(--text);
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.55); }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(124, 58, 237, 0.6);
            box-shadow: 0 0 0 0.25rem rgba(124, 58, 237, 0.18);
            color: var(--text);
        }

        .app-sql-block {
            background: rgba(255, 255, 255, 0.08);
            padding: .72rem .85rem;
            border-radius: .46rem;
            border: 1px solid var(--border);
            word-break: break-all;
        }

        pre.json-result {
            background: rgba(255, 255, 255, 0.08);
            padding: 1.1rem;
            border-radius: .5rem;
            border: 1px solid var(--border);
            color: rgba(255, 255, 255, 0.86);
        }

        #query {
            padding: 12px;
            font-size: 1rem;
        }

        button[type="submit"] {
            padding: 12px;
            font-weight: 500;
        }

        /* ✅ NEW: query display box */
        .app-query-box {
            background: rgba(124, 58, 237, 0.14);
            border: 1px solid rgba(124, 58, 237, 0.28);
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }

        /* Buttons (Bootstrap classes kept) */
        .btn {
            border-radius: 0.85rem;
            transition: transform 140ms ease, filter 140ms ease, background-color 140ms ease, border-color 140ms ease, box-shadow 140ms ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0px); }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border: 0;
            box-shadow: 0 12px 28px rgba(124, 58, 237, 0.24);
        }
        .btn-primary:hover { filter: brightness(1.05); }
        .btn-primary:active { filter: brightness(0.98); }

        .btn-outline-dark,
        .btn-outline-secondary,
        .btn-outline-primary {
            color: var(--text) !important;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
        }
        .btn-outline-dark:hover,
        .btn-outline-secondary:hover,
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.22), rgba(236, 72, 153, 0.14));
            border-color: rgba(255, 255, 255, 0.24) !important;
        }

        .btn-light {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: var(--text);
        }
        .btn-light:hover {
            background: rgba(255, 255, 255, 0.14);
            color: var(--text);
        }

        .btn.disabled,
        .btn:disabled {
            opacity: 0.55 !important;
        }

        code { color: rgba(255, 255, 255, 0.88); }
        .text-success { color: #34d399 !important; }

        .app-summary {}

        /* Utilities */
        .app-error {
            color: var(--danger);
            font-size: .96rem;
        }
    </style>
</head>

<body>

<div class="app-container">

    <h2 class="mb-4 text-center">SQL Helper</h2>

    <!-- FORM -->
    <form id="queryForm" method="POST" action="{{ route('query.process') }}">
        @csrf

        <div class="mb-2">
            <input 
                id="query"
                name="query"
                type="text"
                class="form-control"
                placeholder="Ask in natural language…"
                autofocus
                autocomplete="off"
            >
        </div>

        <div id="clientError" class="app-error mb-2" style="display:none;"></div>

        <button id="submitBtn" type="submit" class="btn btn-primary w-100">
            Submit
        </button>
    </form>

    <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
        <a href="{{ route('tables.view') }}" class="btn btn-outline-dark">
            View Available Data
        </a>

        @if(request()->routeIs('home'))
            <a class="btn btn-outline-secondary disabled" aria-disabled="true" tabindex="-1">
                Go back
            </a>
        @else
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                Go back
            </a>
        @endif
    </div>

    <!-- NEW: SHOW USER QUERY -->
    @if(isset($query) && $query)
        <div class="app-query-box">
            <strong>You asked:</strong> {{ $query }}
        </div>
    @endif

    <!-- SUGGESTIONS -->
    <div class="mt-4">
        <div class="fw-semibold mb-2">Try a suggested query:</div>

        <div class="d-flex flex-column gap-2">
            <button type="button" class="btn btn-outline-secondary suggested-query-btn"
                data-query="How many skincare products sold in last 20 days">
                How many skincare products sold in last 20 days
            </button>

            <button type="button" class="btn btn-outline-secondary suggested-query-btn"
                data-query="Compare skincare and haircare sales">
                Compare skincare and haircare sales
            </button>

            <button type="button" class="btn btn-outline-secondary suggested-query-btn"
                data-query="moisturiser sales in last 14 days">
                Moisturiser sales in last 14 days
            </button>
        </div>
    </div>

    @if(isset($history) && count($history))
    <div class="mt-4">
        <div class="fw-semibold mb-2">Recent Searches:</div>
        <div class="d-flex flex-wrap gap-2">
            @foreach($history as $item)
                <button 
                    type="button" 
                    class="btn btn-outline-secondary btn-sm history-btn"
                    data-query="{{ $item }}"
                >
                    {{ $item }}
                </button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- SQL -->
    @if(isset($sqlDisplay) && $sqlDisplay)
        <div class="mt-4">
            <div class="fw-semibold mb-1">Executable SQL:</div>

            <div class="position-relative">
                <div id="sqlText" class="app-sql-block pe-5">
                    <code>{{ $sqlDisplay }}</code>
                </div>

                <button onclick="copySQL()" class="btn btn-sm btn-light position-absolute top-0 end-0 m-2" aria-label="Copy SQL">
                    <i class="bi bi-clipboard"></i>
                </button>
            </div>

            <small id="copyMsg" class="text-success" style="display:none;">Copied!</small>
        </div>
    @endif

    <!-- MESSAGE -->
    @if(isset($message) && $message)
        <div class="alert alert-info mt-3">
            {{ $message }}
        </div>
    @endif

    <!-- RESULT -->
    @if(isset($result) && $result)
    <div class="mt-4">
        @if(isset($result[0]->total_quantity))
            <div class="summary-block">
                Total Sales: {{ $result[0]->total_quantity }}
            </div>
        @endif

        @if(isset($result[0]->category))
            <div class="row mt-3">
                @foreach($result as $row)
                    <div class="col-md-6">
                        <div class="summary-block text-center">
                            {{ ucfirst($row->category) }} <br>
                            {{ $row->total_sales }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

</div>

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('queryForm');
    const input = document.getElementById('query');
    const btn = document.getElementById('submitBtn');
    const clientError = document.getElementById('clientError');

    // suggestions
    document.querySelectorAll('.suggested-query-btn').forEach(b => {
        b.addEventListener('click', function() {
            input.value = this.dataset.query;
            input.focus();
        });
    });

    form.addEventListener('submit', function (e) {
        const value = input.value.trim();

        if (!value) {
            showError("Please enter your query.");
            e.preventDefault();
            return;
        }

        if (/^\d+$/.test(value)) {
            showError("Query must contain letters.");
            e.preventDefault();
            return;
        }

        btn.disabled = true;
        btn.innerText = "Searching...";
    });

    input.addEventListener('input', hideError);

    function showError(msg) {
        clientError.textContent = msg;
        clientError.style.display = "block";
    }

    function hideError() {
        clientError.textContent = "";
        clientError.style.display = "none";
    }

});

document.querySelectorAll('.history-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('query').value = this.dataset.query;
    });
});
function copySQL() {
    const text = document.getElementById("sqlText").innerText;

    navigator.clipboard.writeText(text).then(() => {
        const msg = document.getElementById("copyMsg");
        msg.style.display = "inline";

        setTimeout(() => {
            msg.style.display = "none";
        }, 1500);
    });
}

</script>

</body>
</html>