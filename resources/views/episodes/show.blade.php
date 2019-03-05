@extends('layouts.app')

<script>
    //use json_encode(). It'll convert from native PHP types to native Javascript types

    var loggedInData = {!! json_encode(Auth::check()) !!}

    //Data for likes vue.js
    var likeData = {
        likedClass: "green",
        normalClass: "grey"
    }

    var playlistData = {
        playlist: {!! json_encode($playlist) !!}
    }

    var episodeData = {
        episode: {!! json_encode($episode) !!}
    }

    var programData = {
        program: {!! json_encode($program) !!}
    }

    var data = {
        loggedInData: loggedInData,
        likeData: likeData,
        playlistData: playlistData,
        shared: false,
        playlisted: false,
        episodeData: episodeData,
        programData: programData

    }

</script>

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script>
$(document).ready(function() {

    $("#episode-content img").each(function() {
        $(this).attr("src", $(this).attr("src").replace("http://", "https://"));
        //console.log($(this).attr("srcset"));
        $(this).attr("srcset", $(this).attr("srcset").replace(/http:/g, "https:"));
    });

});

$(document).ready(function() {
   $("#episode-content img").each(function(){ 
      var image = $(this); 
      if(image.context.naturalWidth == 0 || image.readyState == 'uninitialized'){  
         $(image).unbind("error").hide();
      } 
   }); 
});
</script>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
$(document).ready(function() {
    (adsbygoogle = window.adsbygoogle || []).push({});
});
</script>


@section('content')

    <div id="episode-container" class="container" xmlns:v-on="http://www.w3.org/1999/xhtml">
        <div class="row">
            <div class="col-sm-7">


                <!-- LEFT SIDE -->
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8">

                            <div class="sm2-bar-ui textured flat full-width">

                                <div class="bd sm2-main-controls">

                                    <div class="sm2-inline-texture"></div>
                                    <div class="sm2-inline-gradient"></div>

                                    <div class="sm2-inline-element sm2-button-element">
                                        <div class="sm2-button-bd">
                                            <a href="#play" class="sm2-inline-button sm2-icon-play-pause">Play /
                                                pause</a>
                                        </div>
                                    </div>

                                    <div class="sm2-inline-element sm2-inline-status">

                                        <div class="sm2-playlist">
                                            <div class="sm2-playlist-target">
                                                <!-- playlist <ul> + <li> markup will be injected here -->
                                                <!-- if you want default / non-JS content, you can put that here. -->
                                                <noscript><p>JavaScript is required.</p></noscript>
                                            </div>
                                        </div>

                                        <div class="sm2-progress">
                                            <div class="sm2-row">
                                                <div class="sm2-inline-time">0:00</div>
                                                <div class="sm2-progress-bd">
                                                    <div class="sm2-progress-track">
                                                        <div class="sm2-progress-bar progress-bar-info"></div>
                                                        <div class="sm2-progress-ball">
                                                            <div class="icon-overlay"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="sm2-inline-duration">0:00</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="bd sm2-playlist-drawer sm2-element">

                                    <div class="sm2-inline-texture">
                                        <div class="sm2-box-shadow"></div>
                                    </div>

                                    <!-- playlist content is mirrored here -->
                                    <div class="sm2-playlist-wrapper">
                                        <ul class="sm2-playlist-bd">
                                            <li><a href="{{$episode->file_url}}"></a></li>
                                        </ul>
                                    </div>

                                </div>

                            </div>
                        </div>


                    </div>
                </div>


                <div class="container">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3>{!! htmlspecialchars_decode($episode->title) !!}</h3>


                                    <div class="row-picture">

                                        <a href="{{"/".$episode->program->slug}}">
                                            <img style="height: 48px;width: 48px!important;float: left;"
                                                 src="{{$program->logo->medium}}"
                                                 alt="icon">
                                        </a>

                                        <h6>{{ link_to($episode->program->slug, $title = $episode->program->name, $attributes = array('class' => 'margin-left-5'), $secure = null) }}</h6>


                                        <div class="btn-toolbar margin-bottom-10">
                                            <div id="subscription" class="btn-group btn-group-xs btn-group-solid">
                                                <subscribe></subscribe>
                                                <unsubscribe></unsubscribe>
                                                <subscriptions></subscriptions>
                                            </div>
                                            <h4 class="pull-right">
                                            @if ($episode->played_count>0)
                                                <span class="text-capitalize">{{intval($episode->played_count)}} plays</span>
                                            @endif
                                            </h4>
                                        </div>


                                    </div>

                                    <hr>

                                    <div class="list-unstyled">
                                        <!-- left -->
                                        <a v-show="!playlisted" v-on:click="addToPlaylist({{json_encode($episode)}})" class="tooltips" style="margin-right: 20px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Adicionar na Playlist"><i class="fa fa-plus" style="margin-right: 5px;"></i>Adicionar</a>

                                        <a v-on:click="shared = !shared" class="tooltips" style="margin-right: 20px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Compartilhar"><i class="fa fa-share" style="margin-right: 5px;"></i>Compartilhar</a>

                                        <a href="{{$episode->file_url}}" download="{{ basename($episode->file_url).PHP_EOL }}" class="tooltips" style="margin-right: 20px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Download"><i class="fa fa-download" style="margin-right: 5px;"></i>Download</a>

                                        <div id="likes" class="btn-group btn-group-xs btn-group-solid pull-right tooltips">
                                            <like></like>
                                            <dislike></dislike>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <transition name="fade">
                    <div class="container" v-show="shared" style="display: none;">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="panel panel-default">
                                    <div class="panel-body">

                                        <div class="socicons">
                                        <a target="_blank" href="https://twitter.com/share?url={{Request::url()}}&via=PodflixBrasil&related=twitterapi%2Ctwitter&hashtags=Podcast,NowPlaying&text={{urlencode($episode->title)." 🔊 ♫"}}"
                                           class="socicon-btn socicon-btn-circle socicon-solid bg-blue font-white bg-hover-grey-salsa socicon-twitter tooltips" data-original-title="Twitter"></a>
                                        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{Request::url()}}"
                                           class="socicon-btn socicon-btn-circle socicon-solid bg-blue font-white bg-hover-grey-salsa socicon-facebook tooltips" data-original-title="Facebook"></a>
                                        <a target="_blank" href="https://plus.google.com/u/0/share?url={{Request::url()}}&source=podflix&hl=pt&soc-platform=1&soc-app=130"
                                           class="socicon-btn socicon-btn-circle socicon-solid bg-dark font-white bg-hover-grey-salsa socicon-google tooltips" data-original-title="Google"></a>
                                        <a target="_blank" href="https://www.linkedin.com/shareArticle?source=Podflix&url={{Request::url()}}"
                                           class="socicon-btn socicon-btn-circle socicon-solid bg-blue font-white bg-hover-grey-salsa socicon-linkedin tooltips" data-original-title="Linkedin"></a>
                                        <a target="_blank" href="https://web.skype.com/share?source=podflix&url={{Request::url()}}&source=podflix&hl=pt&soc-platform=1&soc-app=130"
                                           class="socicon-btn socicon-btn-circle socicon-solid bg-green font-white bg-hover-grey-salsa socicon-skype tooltips" data-original-title="Skype"></a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>


                <div class="container">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div id="episode-content" class="panel-body">
                                    {!! htmlspecialchars_decode($episode->content) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- END LEFT SIDE -->

            <!-- RIGHT SIDE -->
            <div class="col-sm-4 col-sm-offset-1 blog-sidebar">
                <div class="sidebar-module sidebar-module-inset">
                    <div class="panel panel-default">

<!-- Podflix-Web-responsivo -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client=""
     data-ad-slot=""
     data-ad-format="auto"></ins>


                        <hr>

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
            </div>

        </div>
    </div>



@endsection