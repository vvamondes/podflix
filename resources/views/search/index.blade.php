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
                            <span class="caption-subject font-black bold uppercase">Resultados</span>
                        </div>
                    </div>
                    <div class="portlet-body ">
                        <div class="mt-element-card mt-element-overlay">
                            <ul class="list-inline">
                            @if (isset($programs) && count($programs)>0)
                                @foreach ($programs as $index=>$program)
                                    <li>
                                        <div class="mt-card-item">
                                            <div class="mt-card-avatar mt-overlay-3">
                                                <a href="/{{$program->slug}}">
                                                        <img src="{{$program->logo->small}}" alt="icon">
                                                    </a>
                                            </div>
                                            <div class="mt-card-content">
                                                <h5 class="mt-card-name">{{ link_to($program->slug, $title = $program->name, $attributes = array(), $secure = null) }}</h5>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                @endif  
                            </ul>
                            <hr>
                            <ul class="list-inline">
                                @if (isset($episodes) && count($episodes)>0)
                                    @foreach ($episodes as $index=>$episode)
                                    <li>
                                        <div class="mt-card-item">
                                            <div class="mt-card-avatar mt-overlay-3">
                                                <a href="{{$episode->program->slug."/".$episode->slug}}">
                                                        @if(count($episode->program->logo)>0)<img src="{{$episode->program->logo->small}}" alt="icon">@endif
                                                    </a>
                                            </div>
                                            <div class="mt-card-content">

                                                <h5 class="mt-card-name">{{ link_to($episode->program->slug."/".$episode->slug, $title = htmlspecialchars_decode($episode->title), $attributes = array(), $secure = null) }}</h5>

                                                <p class="mt-card-desc font-grey-mint">
                                                    {{ link_to($episode->program->slug, $title = $episode->program->name, $attributes = array(), $secure = null) }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                {{ $episodes->appends(['q' => Request::get('q') ])->links() }}
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- END : USER CARDS -->
    </div>
    <!-- END CONTENT -->
@endsection