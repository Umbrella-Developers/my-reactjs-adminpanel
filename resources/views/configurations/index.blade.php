@extends('layouts.app')

@section('title', 'Site Configurations')
@section('page_css')
    <link href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css" />
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Configurations</h3>
        </div>
    </div>
    <div class="">


        
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs " role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true" >
                        <i class="fa fa-gear"></i> General Setting
                    </button>
                </li>
                
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false" >
                    <i class="fa fa-share"></i> Social Media
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="false" >
                    <i class="fa fa-envelope"></i> Email Template
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Tab 1 -->
                <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                    <form class="mt-3" method="POST" action="configurations/update/generalSettingsUpdate" enctype="multipart/form-data">
                        @csrf
                        
                        @foreach ($generalSetting as $setting)
                            @if($setting->input_type == 'text' && $setting->name != 'copyright')
                                <div class="form-group mb-3">
                                    <label for="{{$setting->name}}"class="mb-1"><strong>{{ ucfirst(str_replace('_', ' ', $setting->name)) }}</strong></label>
                                    <input type="text" class="form-control" id="{{$setting->name}}" name="{{$setting->name}}" placeholder="Enter {{ ucfirst(str_replace('_', ' ', $setting->name)) }}" value="{{$setting->value}}">
                                </div>
                            @endif
                            @if($setting->input_type == 'checkbox' && $setting->name != 'coming_soon' && $setting->name != 'status')
                                <div class="form-group mb-3">
                                    <label for="{{$setting->name}}" class="mb-1"><strong>{{ ucfirst(str_replace('_', ' ', $setting->name)) }}</strong></label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" value="0" name="{{$setting->name}}">
                                        <input class="form-check-input" type="checkbox" id="{{$setting->name}}" name="{{$setting->name}}" {{ old($setting->name, $setting->value) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{$setting->name}}">{{ ucfirst(str_replace('_', ' ', $setting->name)) }}</label>
                                    </div>
                                </div>
                            @endif
                            @if($setting->input_type == 'date')
                                <div class="form-group mb-3">
                                    <label for="{{$setting->name}}" class="mb-1"> <strong>{{ ucfirst(str_replace('_', ' ', $setting->name)) }}</strong></label>
                                    <div class="input-group date" id="datetimepicker{{$setting->name}}" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker{{$setting->name}}" id="{{$setting->name}}" name="{{$setting->name}}" placeholder="Enter {{ ucfirst(str_replace('_', ' ', $setting->name)) }}" value="{{ old($setting->name, $setting->value) }}" />
                                        <div class="input-group-append" data-target="#datetimepicker{{$setting->name}}" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($setting->input_type == 'file')
                                <div class="form-group mb-3">
                                    <label for="{{$setting->name}}" class="mb-1"><strong>{{ ucfirst(str_replace('_', ' ', $setting->name)) }}</strong></label>
                                    <input type="file" class="form-control" id="{{$setting->name}}" name="{{$setting->name}}" accept="image/*">
                                    <small class="text-danger">Current file: <a href="{{$setting->value}}" target="_blank">{{ $setting->value }}</a></small>
                                </div>
                            @endif
                            
                        @endforeach
                        <button type="submit" class="btn btn-primary">Update General Settings</button>
                    </form>
                </div>
                <!-- Tab 2 -->
                <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                    <form class="mt-3" method="POST" action="configurations/update/socialMediaUpdate">
                        @CSRF
                        @foreach ($socialMedia as $setting)
                        <div class="form-group mb-3">
                            <label for="{{$setting->name}}" class="mb-1"><strong>{{ ucfirst(str_replace('_', ' ', $setting->name)) }}</strong></label>
                            <input type="text" class="form-control" id="{{$setting->name}}" name="{{$setting->name}}" placeholder="Enter {{ ucfirst(str_replace('_', ' ', $setting->name)) }}" value="{{$setting->value}}">
                        </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Update Social Media</button>
                    </form>
                </div>
                <!-- Tab 3 -->
                <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                    
                        @php
                            $k = 1;
                        @endphp
                        @foreach ($emailTemplate as $setting)
                            <form class="mt-3" method="POST" action="configurations/update/emailTemplatesUpdate">
                                @CSRF
                                <div class="form-group mb-3">
                                    <label for="{{$setting->name}}" class="mb-1"><strong>{{ ucfirst(str_replace('_', ' ', $setting->name)) }}: </strong></label>
                                    {{-- <input type="text" class="form-control" id="{{$setting->name}}" name="{{$setting->name}}" placeholder="Enter {{ ucfirst(str_replace('_', ' ', $setting->name)) }}" value="{{$setting->value}}"> --}}
                                    <textarea rows="5"  class="form-control" id="editor{{$k++}}" name="{{$setting->name}}" placeholder="Enter {{ ucfirst(str_replace('_', ' ', $setting->name)) }}">{{$setting->value}}</textarea>
                                    
                                </div>
                                @if($setting->description)
                                    @php
                                        $shortcodes = explode(',', $setting->description);
                                    @endphp
                                    @if(is_array($shortcodes) && count($shortcodes))
                                        <p class="mt-3">Available shortcodes are: 
                                            @foreach ($shortcodes as $shortcode)
                                                <code>{{'{' . $shortcode . '}'}}</code> 
                                            @endforeach
                                        </p>
                                    @endif
                                @endif
                                <button type="submit" class="btn btn-primary mb-3">Update</button>
                            </form>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
        
@endsection

@section('page_js')
<!-- Inside the <head> -->

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/ckeditor5/ckeditor5.css') }}">
    <script type="importmap">
		{
			"imports": {
				"ckeditor5": "{{ asset('assets/ckeditor5/ckeditor5.js') }}",
				"ckeditor5/": "{{ asset('assets/ckeditor5/') }}/"
			}
		}
    </script>
    <script type="module" src="{{ asset('assets/ckeditor_main.js') }}"></script>

<script>
    (function ($) {
        $(document).ready(function(){
            $('.datetimepicker-input').datepicker({
                format: 'mm/dd/yyyy',  // You can change the format as per your requirement
                autoclose: true,
                todayHighlight: true,
            });
        });
    })(jQuery);
</script>
@endsection

