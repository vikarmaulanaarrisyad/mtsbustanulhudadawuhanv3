@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    @includeIf('admin.dashboard.small_box')
    @include('admin.dashboard.info_ppdb')
@endsection
