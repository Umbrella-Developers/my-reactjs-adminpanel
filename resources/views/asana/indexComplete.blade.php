<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asana Projects</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Full height background with a gradient */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f06, #ff9);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Container for content */
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 100%;
            margin: 0 auto;
        }

        /* Card header styling */
        .card-header {
            background-color: #6a11cb;
            color: #fff;
            cursor: pointer;
            border: none;
            position: relative;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header:hover {
            background-color: #5a0dbb;
        }
        .card-body {
            padding: 1.25rem;
            background-color: #f8f9fa;
            transition: max-height 0.35s ease;
        }
        .btn-link {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            display: block;
            width: 100%;
            text-align: left;
        }
        .btn-link:hover {
            color: #d3d3d3;
            text-decoration: none;
        }
        .card-number {
            background-color: #5a0dbb;
            color: #fff;
            border-radius: 50%;
            padding: 5px 10px;
            font-weight: bold;
            display: inline-block;
            margin-right: 10px;
        }
        .total-fields {
            background-color: #6a11cb;
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .tree ul {
            padding-left: 20px;
            list-style-type: none;
            margin: 0;
            position: relative;
        }
        .tree ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 10px;
            height: 100%;
            width: 2px;
            background: #6a11cb;
        }
        .tree li {
            position: relative;
            margin: 0;
            padding: 0 0 1em 1em;
            font-size: 16px;
            color: #333;
        }
        .tree li::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 0;
            bottom: 1em;
            width: 2px;
            background: #6a11cb;
        }
        .tree li::after {
            content: '';
            position: absolute;
            left: -10px;
            top: 1em;
            width: 10px;
            height: 0;
            border-top: 2px solid #6a11cb;
        }
        .tree .card {
            border: none;
            border-radius: 0;
            margin-bottom: 0;
            transition: box-shadow 0.3s ease;
        }
        .tree .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .tree .card-header {
            border-radius: 0;
            background-color: #6a11cb;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .tree .card-body {
            background-color: #f8f9fa;
            max-height: 1024px; /* Adjust height as needed */
            overflow: auto; /* Enable scrolling */
        }
        .tree .card-body::-webkit-scrollbar {
            width: 8px; /* Set the width of the scrollbar */
        }
        .tree .card-body::-webkit-scrollbar-thumb {
            background: #6a11cb; /* Set the color of the scrollbar thumb */
            border-radius: 10px;
        }
        .tree .card-body::-webkit-scrollbar-track {
            background: #f8f9fa; /* Set the color of the scrollbar track */
        }
        .collapse {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }
        .collapse.show {
            max-height: 1000px; /* Adjust height as needed */
        }
        .collapse.show .card-body {
            padding: 1.25rem;
        }
        .tree .card-body p {
            margin-bottom: 0;
        }
        .tree .card-body h4 {
            margin: 0;
        }
        .tree ul ul {
            padding-left: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #6a11cb;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5a0dbb;
        }

        .pagination-wrapper .pagination li:first-child span::before,
        .pagination-wrapper .pagination li:first-child a::before,
        .pagination-wrapper .pagination li:last-child span::after,
        .pagination-wrapper .pagination li:last-child a::after {
            content: none !important;
        }

        .pagination-wrapper .pagination li:first-child a,
        .pagination-wrapper .pagination li:first-child span,
        .pagination-wrapper .pagination li:last-child a,
        .pagination-wrapper .pagination li:last-child span {
            padding-left: 0;
            padding-right: 0;
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Asana Projects</h1>

         <!-- Sync Donations Button -->
         <div class="mb-4">
            <a href="{{ url('/syncDonations') }}" class="btn btn-primary">Sync Donations</a>
        </div>
        
        <div class="search-container">
            {{-- <form method="GET" action="{{ url('/search') }}" class="form-inline">
                <label for="query" class="mr-2">HIT JOB ID:</label>
                <input type="text" name="query" id="query" class="form-control mr-2" placeholder="Search custom fields" value="{{ $querySearch }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form> --}}
        </div>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
        <div class="row past_donation">
            <div class="col-md-12">
                    <h3>Past Donations</h3>
                    <p>15 Total</p>
            </div>
        </div>
        
        <h2><b>Sum of E-Waste:</b>{{$totalSum}}</h2>
        <div class="row">
            <div class="col-md-12" id="donation-list">
            {{-- @foreach($projects as $project) --}}
                
                    <div id="collapse{{ $donations->id }}" class="collapse show" aria-labelledby="heading{{ $donations->id }}">
                            @if($donations->paginatedDonations->count())
                                
                                    @php
                                        $startIndex = ($donations->paginatedDonations->currentPage() - 1) * $donations->paginatedDonations->perPage() + 1;
                                    @endphp
                                    <div class="accordion mt-3 mb-4" id="donationsAaccordion">
                                        @foreach($donations->paginatedDonations as $index => $donation)
                                            <div class="card donation-item accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <div
                                                    type="button"
                                                    class="accordion-button @if($index > 0)  collapsed @endif"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#accordion_{{$index}}"
                                                    aria-expanded="@if($index == 0) true @else false @endif"
                                                    aria-controls="accordion_{{$index}}"
                                                    >
                                                        @if($donation->filtered_fields) 
                                                            <div class="started-date">{{ convertToDateFormat($donation->filtered_fields->received_at) }}</div>
                                                            <div class="job-id">Job ID: {{$donation->filtered_fields->job_id}}</div>
                                                        @endif
                                                    </div>
                                                </h2>
                                                <div
                                                    id="accordion_{{$index}}"
                                                    class="accordion-collapse  @if($index == 0) show @else collapse @endif"
                                                    data-bs-parent="#donationsAaccordion"
                                                >
                                                    <div class="accordion-body">
                                                        <!-- Available cutom fields -->
                                                         <!-- 'job_id',
                                                                'received_at'
                                                                'first_name'
                                                                'last_name',
                                                                'street_address',
                                                                'city'
                                                                'state'
                                                                'zip'
                                                                'organization_name'
                                                                'impact_report_link'
                                                                'donation_receipt_link'
                                                                'apllets_received'
                                                                'buld_ewaste_weight'
                                                                'donation_stage'
                                                                'record_id'

                                                                Note: To add more variables dd($donation->fields) and find the gid of required field add it in the filterFieldsByGids() helper function 
                                                                        and give it a required name.
                                                         -->
                                                        <!-- Available cutom fields -->
                                                         <div class="donation-body">
                                                            <div class="donor-address mb-12">
                                                                Picked up at 
                                                                @if(isset($donation->filtered_fields->street_address))
                                                                    {{ $donation->filtered_fields->street_address }},
                                                                @endif
                                                                @if(isset($donation->filtered_fields->city))
                                                                    {{ $donation->filtered_fields->city }},
                                                                @endif
                                                                @if(isset($donation->filtered_fields->state))
                                                                    {{ $donation->filtered_fields->state }} 
                                                                @endif
                                                                @if(isset($donation->filtered_fields->zip))
                                                                    {{ $donation->filtered_fields->zip }}
                                                                @endif
                                                            </div>
                                                            @if(isset($donation->filtered_fields->received_at))
                                                                <div class="completed-on mb-3">
                                                                    Completed on {{ convertToDateFormat($donation->filtered_fields->received_at) }}
                                                                </div>
                                                            @endif
                                                            <div class="receipt-buttons mb-2">
                                                                @if(isset($donation->filtered_fields->impact_report_link))
                                                                    <a class="btn btn-primary impact-link-button" href="{{ $donation->filtered_fields->impact_report_link }}">
                                                                        Receipt <i class="fas fa-download"></i>
                                                                    </a>
                                                                
                                                                @endif
                                                                @if(isset($donation->filtered_fields->donation_receipt_link))
                                                                    <a class="btn btn-text fw-bold text-secondary data-destruction-link" href="{{ $donation->filtered_fields->donation_receipt_link }}"> Certificat of Data Destruction </a>
                                                                @endif
                                                            </div>
                                                         </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            
                                        @endforeach
                                    </div>
                                

                                <!-- Display the pagination links -->
                                <nav>
                                    <ul class="pagination">
                                        {{ $donations->paginatedDonations->links('pagination::bootstrap-4') }}
                                    </ul>
                                </nav>
                                
                            @else
                                <p>No donations available.</p>
                            @endif
                        
                    </div>
                
            {{-- @endforeach --}}
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
@section('page_js')
    <script>
        (function($){
            $(document).ready(function() {
                // $('.accordion-button').click(function() {
                //     console.log('clicked');
                //     var $span = $(this).find('span');
                //     if ($(this).hasClass('collapsed')) {
                //         $span.text('+');
                //     } else {
                //         $span.text('-');
                //     }
                // });
            });
        })(jQuery);
    </script>
@endsection