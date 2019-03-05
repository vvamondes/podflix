@extends('layouts.app')

<script>

    var loggedInData = {!! json_encode(Auth::check()) !!}

    //Data for subscription vue.js
    var programData = {
        program: {!! json_encode($program) !!}
    }

    var data = {
        loggedInData: loggedInData,
        programData: programData,
        searchQuery: '',
    }

</script>

@section('content')

    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PROFILE SIDEBAR -->
                    <div class="profile-sidebar">

                        <div class="row-picture">

                            <a href="/{{$program->slug}}">
                                <img style="width: 100%!important;"
                                     src="{{$program->logo->medium}}"
                                     alt="logo {{$program->slug}}">
                            </a>

                            <h2>{!! $program->name !!}</h2>

                            <div class="profile-userbuttons">
                                <div class="btn-toolbar margin-bottom-10">
                                    <div id="subscription" class="btn-group btn-group-xs btn-group-solid">
                                        <subscribe></subscribe>
                                        <unsubscribe></unsubscribe>
                                        <subscriptions></subscriptions>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- PORTLET MAIN -->
                            <div class="portlet light ">
                                <div>
                                    <span class="profile-desc-text"> {{$program->description}} </span>
                                    <div class="margin-top-20 profile-desc-link">
                                        @if($program->site!="")
                                        <i class="fa fa-globe"></i>
                                        <a href="{{$program->site}}">{{$program->site}}</a>
                                        @endif
                                    </div>
                                    @foreach ($program->twitters as $index=>$twitter)
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-twitter"></i>
                                            <a href="https://twitter.com/{{$twitter->name}}">{{$twitter->name}}</a>
                                        </div>
                                    @endforeach
                                    @foreach ($program->facebooks as $index=>$facebook)
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-facebook"></i>
                                            <a href="https://www.facebook.com/{{$facebook->name}}">{{$facebook->name}}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        <!-- END PORTLET MAIN -->



                    </div>
                    <!-- END BEGIN PROFILE SIDEBAR -->
                    <!-- BEGIN PROFILE CONTENT -->
                    <div class="profile-content">
                        <div class="row">


                            <div class="col-md-7">
                                <div class="portlet light  tasks-widget">
                                    <div class="portlet-title">
                                        <div class="caption caption-md">
                                            <i class="icon-bar-chart theme-font hide"></i>
                                            <span class="caption-subject font-blue-madison bold uppercase">Episódios</span>
                                            <span class="caption-helper">{{$program->episodes->count()}}</span>
                                        </div>
                                    </div>

                                    
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <input class="form-control" type="text" placeholder="Filtrar" v-model="searchQuery">
                                        </div>
                                    </div>
                                    

                                    <div class="portlet-body">
                                        <div class="task-content">
                                            <div class="scroller" style="height: 600px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                                <!-- START TASK LIST -->
                                                <episodes-list></episodes-list>
                                                <!-- END START TASK LIST -->
                                            </div>
                                        </div>
                                        <div class="task-footer">
                                            <div class="btn-arrow-link pull-right">
                                                <a href="javascript:;"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-5">
                                <!-- RIGHT SIDE -->
                                <div class="sidebar-module sidebar-module-inset">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            
                                        <!-- 320x100 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:320px;height:100px"
     data-ad-client=""
     data-ad-slot=""></ins>

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>

@endsection