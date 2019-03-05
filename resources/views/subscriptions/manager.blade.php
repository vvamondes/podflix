@extends('layouts.app')

<script>
    var subscriptionData = {
        subscriptions : {!! json_encode($subscriptions) !!}
    };
    var data = {
        subscriptionData: subscriptionData,
    }
</script>

@section('content')
<div class="container" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">

            <div class="portlet light portlet-fit ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class=" icon-layers font-green"></i>
                        <span class="caption-subject font-green-madison bold uppercase">Podcasts Assinados</span>
                        <span class="caption-helper">{{$subscriptions->count()}}</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="mt-element-list">
                        <div class="mt-list-container list-simple ext-1">

                            <div id="subscriptions-list">
                                <ul>
                                    <li class="mt-list-item"
                                            is="subscription-program"
                                            v-for="(subscription, index) in subscriptionData.subscriptions"
                                            v-bind:subscription="subscription"
                                    >
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


    </div>
</div>
@endsection
