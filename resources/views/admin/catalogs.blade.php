@extends('layouts.app')

@section('content')
    <!-- BEGIN CONTAINER -->
    <div class="container">
        <!-- BEGIN : USER CARDS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light portlet-fit bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject font-black bold uppercase">Admin - Catalogs</span>
                            </div>
                        </div>
                        <div class="portlet-body ">
                            <div class="mt-element-card mt-element-overlay">
                                @foreach($catalogs as $catalog)
                                    <?php
                                        $selected = array();
                                    ?>
                                    @foreach($catalog->categories as $category)
                                    <?php
                                        $selected[$category->name] = $category->id;
                                    ?>
                                    @endforeach

                                    {{ Form::open(array('url' => '/admin/catalogs', 'method' => 'post')) }}
                                        {!! Form::select('catalog', array($catalog->id => $catalog->name)) !!}
                                        {!! Form::select('categories[]', $categories, $selected, ['multiple' => 'multiple', 'class' => 'admin_selected']) !!}
                                        {!! Form::submit('Salvar') !!}
                                    {{ Form::close() }}
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- END CONTENT -->
@endsection

<style>
    .admin_selected {
        height: 250px !important;
        width: 300px;
    }
</style>