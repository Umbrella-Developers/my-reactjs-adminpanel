@extends('layouts.app')

@section('title', 'Temporary Associations')
@section('page_css')
    <link href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css" />
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Temporary Associations</h3>
            <h2>Please contact the support for further information.</h2>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" href="/logout"><i class="fas fa-unlock"></i> logout</a>
        </div>
    </div>
   <div></div>
        
@endsection
