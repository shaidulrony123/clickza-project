
@extends('backend.layouts.app')
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f7fb;
        }
        h1 {
            margin-bottom: 20px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }
        .card h3 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #555;
        }
        .card p {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .section {
            background: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        table th {
            background: #fafafa;
        }
    </style>
@section('content')
   <div class="dash-section active visitor-section">
        <h1>Visitor Dashboard</h1>

    <div class="cards">
        <div class="card">
            <h3>Today Unique Visitors</h3>
            <p>{{ $todayUniqueVisitors }}</p>
        </div>

        <div class="card">
            <h3>Today Total Hits</h3>
            <p>{{ $todayHits }}</p>
        </div>

        <div class="card">
            <h3>Total Unique Visitors</h3>
            <p>{{ $totalUniqueVisitors }}</p>
        </div>

        <div class="card">
            <h3>Total Hits</h3>
            <p>{{ $totalHits }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Country Wise Unique Visitors</h2>
        <table>
            <thead>
                <tr>
                    <th>Country</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($countryWise as $country)
                    <tr>
                        <td>{{ $country->country_name ?? 'Unknown' }}</td>
                        <td>{{ $country->total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
    <h2>Top Pages</h2>
    <table>
        <thead>
            <tr>
                <th>Page</th>
                <th>Total Visits</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topPages as $page)
                <tr>
                    <td>{{ $page->path }}</td>
                    <td>{{ $page->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    <div class="section">
        <h2>Latest Visits</h2>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Country</th>
                    <th>IP</th>
                    <th>Path</th>
                    <th>Unique?</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestVisits as $visit)
                    <tr>
                        <td>{{ $visit->created_at }}</td>
                        <td>{{ $visit->country_name ?? 'Unknown' }}</td>
                        <td>{{ $visit->ip }}</td>
                        <td>{{ $visit->path }}</td>
                        <td>{{ $visit->is_unique ? 'Yes' : 'No' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
   </div>

@endsection