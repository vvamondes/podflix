@extends('layouts.app')

<script>
    var loggedInData = {!! json_encode(Auth::check()) !!}

    var playlistData = {
        playlist: {!! json_encode($playlist) !!}
    }
    
    var data = {
        loggedInData: loggedInData,
        playlistData: playlistData
    }
</script>

@section('content')
<div class="container" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">

        <div class="portlet light  tasks-widget">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart theme-font hide"></i>
                    <span class="caption-subject font-blue-madison bold uppercase">Playlist</span>
                    <span class="caption-helper">{{count($playlist)}}</span>
                </div>

            </div>
            <div class="portlet-body">
                <div class="task-content">
                    <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                        <playlist></playlist>
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
</div>
@endsection
