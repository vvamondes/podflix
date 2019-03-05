@extends('layouts.app')

@section('content')
    <!-- BEGIN CONTAINER -->
    <div class="container">

        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-12">

                        @if (session('status'))
                        <div class="m-heading-1 note note-success border-green m-bordered">
                            <h3>Programa Cadastrado com sucesso!</h3>
                            <p>Solicitação realizada, em 24h iremos processar.</p>
                        </div>
                        @endif

                            <div class="portlet light " id="form_wizard_1">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class=" icon-layers font-blue"></i>
                                        <span class="caption-subject font-blue bold uppercase"> Adicionar Novo Programa
                                        </span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <form class="form-horizontal form-bordered" action="/feed/request/program/create" id="submit_form" method="POST">
                                     {{ csrf_field() }}
                                        <div class="form-wizard">
                                            <div class="form-body">
                                                <ul class="nav nav-pills nav-justified steps">
                                                    <li>
                                                        <a href="#tab1" data-toggle="tab" class="step">
                                                            <span class="number"> 1 </span>
                                                            <span class="desc">
                                                                <i class="fa fa-check"></i> Informar a URL do feed </span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab2" data-toggle="tab" class="step">
                                                            <span class="number"> 2 </span>
                                                            <span class="desc">
                                                                <i class="fa fa-check"></i> Editar informações </span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab3" data-toggle="tab" class="step">
                                                            <span class="number"> 3 </span>
                                                            <span class="desc">
                                                                <i class="fa fa-check"></i> Confirmar </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div id="bar" class="progress progress-striped" role="progressbar">
                                                    <div class="progress-bar progress-bar-success"> </div>
                                                </div>
                                                <div class="tab-content">
                                                    <div class="alert alert-danger display-none">
                                                        <button class="close" data-dismiss="alert"></button> You have some form errors. Please check below. </div>
                                                    <div class="alert alert-success display-none">
                                                        <button class="close" data-dismiss="alert"></button> Your form validation is successful! </div>
                                                    <div class="tab-pane active" id="tab1">
                                                        <h3 class="block">Feed URL</h3>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Feed URL
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="url" class="form-control" name="feed" required />
                                                                <span class="help-block" for="feed"> Ex: http://feeds.feedburner.com/XXXXXX, 
                                                                <br> http://feeds.soundcloud.com/users/soundcloud:users:XXXXXXXXX/sounds.rss 
                                                                <br> http://www.podcastgarden.com/podcast/podcast-rss.php?id=XXXX</span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- EDITAR -->
                                                    <div class="tab-pane" id="tab2">
                                                        <h3 class="block">Informe mais detalhes do Programa</h3>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Nome do Programa
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="name" required/>
                                                                <span class="help-block"> Informe o Nome do Programa </span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Descrição
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <textarea rows="8" class="form-control" class="form-control" name="description" required></textarea>
                                                                <span class="help-block"> Informe a Descrição </span>
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Logo
                                                                    <span class="required" aria-required="true"> * </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <img name="logo" width="250" height="250" class="preview" />
                                                                <br>
                                                                <input type="text" class="form-control" name="logo" required readonly/>
                                                                <span class="help-block"> * Imagem utilizada no Feed </span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Site URL
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="url" class="form-control" name="site" required />
                                                                <span class="help-block" for="site"> Ex: https://podflix.com.br</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Email
                                                                <span class="required" aria-required="true"> * </span>
                                                            </label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-envelope"></i>
                                                                    </span>
                                                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Twitter</label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                <span class="input-group-addon" id="sizing-addon1">@</span>
                                                                <input type="text" name="twitter" class="form-control" placeholder="Username"> </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Facebook</label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                <span class="input-group-addon" id="sizing-addon1">facebook.com/</span>
                                                                <input type="text" name="facebook" class="form-control" placeholder="Page"> </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Google+</label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                <span class="input-group-addon" id="sizing-addon1">plus.google.com/</span>
                                                                <input type="text" name="googleplus" class="form-control" placeholder="Page"> </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- CONFIRMAR -->
                                                    <div class="tab-pane" id="tab3">
                                                        <h3 class="block">Confirmar</h3>

                                                    <div class="tab-pane active" id="tab1">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Feed URL
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="url" class="form-control" name="feed" readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tab2">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Nome do Programa
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="name" readonly />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Descrição
                                                            </label>
                                                            <div class="col-md-6">
                                                                <textarea rows="8" class="form-control" class="form-control" name="description" readonly></textarea>
                                                            </div>
                                                        </div>


                                                        <div class="form-group ">
                                                            <label class="control-label col-md-3">Logo</label>
                                                            <div class="col-md-6">
                                                                <img name="logo" width="300" height="300" class="preview" />
                                                                <br>
                                                                <input type="text" class="form-control" name="logo" readonly/>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Site
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="url" class="form-control" name="site" readonly />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Email</label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-envelope"></i>
                                                                    </span>
                                                                    <input type="email" name="email" class="form-control" readonly> </div>
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Twitter</label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                <span class="input-group-addon" id="sizing-addon1">@</span>
                                                                <input type="text" name="twitter" class="form-control" readonly> </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Facebook</label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                <span class="input-group-addon" id="sizing-addon1">facebook.com/</span>
                                                                <input type="text" name="facebook" class="form-control" readonly> </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Google+</label>
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                <span class="input-group-addon" id="sizing-addon1">plus.google.com/</span>
                                                                <input type="text" name="googleplus" class="form-control" readonly> </div>
                                                            </div>
                                                        </div>
                                                        


                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <a href="javascript:;" class="btn default button-previous">
                                                            <i class="fa fa-angle-left"></i> Back </a>
                                                        <a href="javascript:;" class="btn btn-outline green button-next"> Continue
                                                            <i class="fa fa-angle-right"></i>
                                                        </a>
                                                        <button type="submit" class="btn green button-submit"> Submit
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
        </div>
        <!-- END CONTENT BODY -->

    </div>
    <!-- END CONTENT -->

    <!-- Adicionando JQuery -->
    <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- Adicionando Javascript -->
    <script type="text/javascript" >

        $(document).ready(function() {

            $("input[name='feed']").on('change', function() {
                $("input[name='feed']").val(this.value);
            });

            $("input[name='name']").on('change', function() {
                $("input[name='name']").val(this.value);
            });

            $("textarea[name='description']").on('change', function() {
                $("textarea[name='description']").val(this.value);
            });

            $("input[name='logo']").on('change', function() {
                $("img[name='logo']").prop('src', this.value);
                $("input[name='logo']").val(this.value);
            });

            $("input[name='site']").on('change', function() {
                $("input[name='site']").val(this.value);
            });

            $("input[name='email']").on('change', function() {
                $("input[name='email']").val(this.value);
            });

            $("input[name='twitter']").on('change', function() {
                $("input[name='twitter']").val(this.value);
            });

            $("input[name='facebook']").on('change', function() {
                $("input[name='facebook']").val(this.value);
            });

            $("input[name='googleplus']").on('change', function() {
                $("input[name='googleplus']").val(this.value);
            });

            
            //Quando o campo cep perde o foco.
            $("input[name='feed']").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var feed = $(this).val();

                //Verifica se campo cep possui valor informado.
                if (feed != "") {

                    var url = window.location.protocol+'//'+window.location.host + "/api/feed/request/program/create/get?feed=" + feed;
                    console.log(url);
                    $.blockUI({ message: '<h3>Carregando...</h3>' });
                    $.getJSON(url, function(dados) {
                        $.unblockUI();
                        if (!("erro" in dados)) {

                            $(".button-next").click();

                            //Atualiza os campos com os valores da consulta.
                            $("input[name='name']").val(dados.name);
                            $("textarea[name='description']").val(dados.description);
                            $("img[name='logo']").prop('src', dados.logo);
                            $("input[name='logo']").val(dados.logo);
                            $("input[name='email']").val(dados.email);
                            $("input[name='site']").val(dados.site);
                        } //end if.

                    });
                    
                } //end if.
                
            });
        });


    </script>
@endsection