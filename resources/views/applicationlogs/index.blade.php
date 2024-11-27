@extends('layouts.app')

@section('title', 'Application Logs')
@section('page_css')
    <link href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css" />
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Application Logs</h3>
        </div>
        <div class="col text-end">
            
        </div>
    </div>
    <div class="">
    <?php // json_encode($data, JSON_PRETTY_PRINT) }}</pre> ?>
    <table class="table "  id="users_table">
        <thead>
            <tr>
                <th >Date</th>
                <th >Message</th>
                <th>Controller</th>
                <th>Method</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
           
            @if($logs->count())
                @foreach($logs as $key => $log)
                    <tr>
                        <td style="widh: 250px;">
                            {{ $log->created_at }}
                        </td style="width: 50%">
                        <td>{{$log->message}}</td>
                        <td>
                            {{ $log->model}}
                        </td>
                        <td>
                            {{ $log->module}}
                        </td>
                        <td style="widht: 100px">
                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#log_modal_{{$log->id}}"
                                >
                                    <i class="fas fa-eye me-1"></i> View
                            </button>
                            
                                <!-- Extra Large Modal -->
                            <div class="modal fade" id="log_modal_{{$log->id}}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel4">Error</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div style="width: 150px;" class="mb-3">
                                                    Date: 
                                                </div>
                                                <div class="col mb-3">
                                                    {{ $log->created_at }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div style="width: 150px;" class="mb-3">
                                                    Message: 
                                                </div>
                                                <div class="col mb-3">
                                                    {{ $log->message }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div style="width: 150px;" class="mb-3">
                                                    Controller: 
                                                </div>
                                                <div class="col mb-3">
                                                    {{ $log->model }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div style="width: 150px;" class="mb-3">
                                                    Method: 
                                                </div>
                                                <div class="col mb-3">
                                                    {{ $log->module }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div style="width: 150px;" class="mb-3">
                                                    Log: 
                                                </div>
                                                <div class="col mb-3">
                                                    {{ $log->log }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div style="width: 150px;" class="mb-3">
                                                    Stack Trace: 
                                                </div>
                                                <div class="col mb-3">
                                                    {{ $log->stack_trace }}
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                
                

            @else

            @endif
            
        </tbody>
        </table>
        {{ $logs->links() }}
</div>
        
                        
        
@endsection

@section('page_js')
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.bootstrap5.js"></script>
    <script>
        //new DataTable('#users_table');
        (function($){
            $(document).ready(function() {
                $('.btn-primary').modal({
                    keyboard: false
                })
            })
        })(jQuery);
    </script>
@endsection