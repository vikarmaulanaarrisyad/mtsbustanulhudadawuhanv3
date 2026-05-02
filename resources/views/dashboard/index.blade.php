@extends($layout)

@section('title', 'Dashboard')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    @includeIf('dashboard.small_box')
@endsection
