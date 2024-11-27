@extends('layouts.app')

@section('title', 'Donations')
@section('page_css')
    
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')

    <!-- Your content section -->
    <div id="content_section" class="container-xxl flex-grow-1 container-p-y">
        @if($donations->count() && $totalSum > 0)
            <div class="row ewaste_poundage">
                <div class="col-md-12 text-center">
                        <h2 class="text-primary">{{ number_format($totalSum, 0) }}</h2>
                        <p>pounds of e-waste eliminated</p>
                </div>
            </div>
            <div class="mb-6 pb-4">
                <hr />
            </div>
        @endif
        <div class="row past_donation">
            <div class="col-md-12">
                    <h3>Past Donations</h3>
                    <p>{{$totalDonations}} Total</p>
            </div>
        </div>
        

        <div class="row">
            <div class="col-md-12" id="donation-list">
            
                
                    <div id="collapse1" class="collapse show" aria-labelledby="heading1">
                        
                            @if($donations->count())
                                
                                    <div class="accordion mt-3 mb-4" id="donationsAaccordion">
                                        @foreach($donations as $index => $donation)
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
                                                                    Received on {{ convertToDateFormat($donation->filtered_fields->received_at) }}
                                                                </div>
                                                            @endif
                                                            <div class="receipt-buttons mb-2">
                                                                @if(isset($donation->filtered_fields->donation_receipt_link))
                                                                    <a class="btn btn-primary impact-link-button" href="{{ $donation->filtered_fields->donation_receipt_link }}">
                                                                        Report <i class="fas fa-download"></i>
                                                                    </a>
                                                                
                                                                @endif
                                                                {{--
                                                                @if(isset($donation->filtered_fields->donation_receipt_link))
                                                                    <a class="btn btn-text fw-bold text-secondary data-destruction-link" href="{{ $donation->filtered_fields->donation_receipt_link }}"> Certificat of Data Destruction </a>
                                                                @endif
                                                                --}}
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
                                        {{ $donations->links('pagination::bootstrap-4') }}
                                    </ul>
                                </nav>
                                
                            @else
                                <p>No donations available.</p>
                            @endif
                        
                    </div>
                
            
            </div>
        </div>
    </div>
@endsection
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