<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ITAD Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />


<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }
    .container {
        padding: 20px;
    }
    .header {
        text-align: center;
        padding: 20px;
    }
    .header h3 {
        margin-top: 10px;
        color: #13AE4B;
        text-align: left;
    }
    .header h5 {
        margin: 0;
        color: #3A3A3A;
        text-align: left;
    }
    nav {
        /* background: linear-gradient(to bottom, #65B04A, #0C9D91); */
        background: linear-gradient(to bottom, #88D124, #0C9D91);
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
    }
    .nav-top {
        align-self: flex-start;
    }
    .nav-middle {
        align-self: center;
        margin-top: auto;
        margin-bottom: auto;
    }
    .nav-bottom {
        align-self: flex-end;
    }
    .stats {
        display: flex;
        justify-content: space-around;
        padding: 0px;
    }
    .stats div {
        text-align: center;
    }
    .stat-devices {
        /* color: #00796b; */
        color: #13AE4B;
    }
    .stat-households {
        /* color: #d32f2f; */
        color: #13909B;
    }
    .stat-users {
        /* color: #1976d2; */
        color: #89D024;
    }
    .stat-ewaste {
        /* color: #388e3c; */
        color: #3A3A3A;
    }
    hr {
        border: none;
        border-top: 2px solid black;
        margin: 0 20px 20px 20px;
    }
    .donations {
        padding: 10px 20px;
    }
    .donations-title {
        padding: 0px 5px;
        margin-bottom: 20px;
    }
    .donations-subtitle {
        padding: 0px 5px;
        margin-bottom: 20px;
    }
    /* .accordion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    } */
    /* .accordion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
    } */
    /* .accordion-title {
        display: flex;
        flex-direction: column;
    } */
    /* .accordion-title .date {
        font-weight: bold;
        color: #333;
    } */
    /* .accordion-title .job-id {
        color: #555;
    } */
    .accordion-title {
        text-align: center;
    }
    .accordion-date {
        text-align: left;
    }
    .accordion-jobid {
        text-align: left;
    }
    .accordion-item {
        margin-bottom: 10px;
    }
    .btn-certificate {
        color: #13AE4B;
    }
    .btn-receipt {
        /* color: #13AE4B; */
    }
    .buttons {
        margin-top: 10px;
    }
    .pagination {
        text-align: center;
        margin: 20px 0;
    }
    .pagination span {
        padding: 0 10px;
        cursor: pointer;
    }
</style>
</head>