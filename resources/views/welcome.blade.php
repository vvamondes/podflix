@extends('layouts.app')

@section('content')
            <!-- BEGIN CONTAINER -->
            <div class="container">

            <!-- BEGIN : USER CARDS -->
            @foreach ($sections as $index => $section)

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light portlet-fit bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject font-black bold uppercase">
                                <a href="{{$section->link}}" title="{{$section->title}}">{{$section->title}}</a></span>
                            </div>
                        </div>
                        <div class="portlet-body ">
                            <div class="mt-element-card mt-element-overlay">
                                <ul class="list-inline">
                                    @foreach ($section->episodes as $index=>$episode)
                                    <li>
                                        <div class="mt-card-item">
                                            <div class="mt-card-avatar mt-overlay-3">
                                                <a href="{{$episode->program->slug."/".$episode->slug}}">
                                                    @if(count($episode->program->logo)>0)<img src="{{$episode->program->logo->small}}" alt="icon">@endif
                                                    <span class="episode-time">{{$episode->duration}}</span>
                                                    <a name="{{ $episode->id }}" href="{{ $episode->file_url }}" title="Play" class="sm2_button"><i class="fa fa-address-book" aria-hidden="true"></i></a>
                                                </a>
                                            </div>
                                            <div class="mt-card-content">

                                                <?php
                                                    $pattern = "/\b({$episode->program->name})\b/";
                                                    if (preg_match($pattern, $episode->title)) {
                                                        $episode->title = preg_replace($pattern, "", $episode->title);
                                                    }
                                                ?>
                                                <h5 class="mt-card-name">{{ link_to($episode->program->slug."/".$episode->slug, $title = htmlspecialchars_decode($episode->title), $attributes = array(), $secure = null) }}</h5>

                                                <p class="mt-card-desc font-grey-mint">
                                                    {{ link_to($episode->program->slug, $title = $episode->program->name, $attributes = array(), $secure = null) }}
                                                </p>

                                                <p class="mt-card-desc font-grey-mint">
                                                    @if(intval($episode->played_count)>10)
                                                        {{intval($episode->played_count) . " plays"}}
                                                    @endif
                                                </p>

                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- END : USER CARDS -->

    </div>
    <!-- END CONTENT -->
@endsection
