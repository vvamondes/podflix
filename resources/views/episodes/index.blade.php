@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Todos os Programas</div>
                <div class="panel-body">
                    <ul>
                    @foreach ($programs as $program)
                        <li>{{ link_to("/admin/program/$program->id", $title = $program->name, $attributes = [], $secure = null) }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
