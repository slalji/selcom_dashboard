@extends('layouts/blank')

@section('content')
<script src="./app/js/nbc.js"></script>
<style>
.col-sm-4 {
    width: 33.33333333%;
    background: white;
    border: 1px solid;
    border-radius: 15px;
    padding: 5px;
    margin: 5px;
}
.plan-feature ul li{
  list-type-style:none !important;
}
.tile_count .tile_stats_count{
  background:white;
}
</style>
<div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div xclass="x_panel">
                  <div class="x_title ">
                    <h1>Your Project Private and Public Keys</h1>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div xclass="x_content">
                    <p class="text-muted font-13 m-b-30">
                    <div id="loading"></div>
                     Save <span class="label" style="background:white; color:#1ABB9C"> Private </span> Key Safely with you by copy pasting. Use this key to sign when creating JWT Bearer Token
                     <span class="label" style="background:white; color:#1ABB9C"> Public</span> is Safe with Selcom. Selcom will use this key to 
                     verify your Selcom API request
                     
                      </p>
                       
                  
                    <!-- Main  for a primary marketing message or call to action -->
                   

                    </div>
                        <div class="container">
                          <div xclass="pricing-table pricing-three-column row">
                          @foreach ($projects as $project)
                          <div class="row tile_count" id="tile">
                     
                      <div class="col-md-6 col-sm-6 col-xs-3 xtile_stats_count">
                                    <span class="count_top"><i class="fa fa"></i><h2>  {{$project->title}}</h2></span>
                                    <div class="count_bottom">Vendor  <h3><i class="green">{{$project->vendor}}</i><h3></div>
                                    <span class="count_bottom" ><h3><i class="fa fa-key" style="color:"></i> Private Key for Signing
                                    </h3><i xclass="green"> {{$project->private}}</i></span>
                                     
                                  </div>
                        
                         
                          </div>
                          <span class="count_bottom" ><h3><i class="fa fa-key" style="color:"></i> Public Key for Signing
                                    </h3><i class="green"> {{$project->public}}</i></span>
                      </div> 
                      @endforeach
                      <div class="clearfix"></div>
                       <a class="btn btn-primary"   href="{{route('home')}}"  > Home <i class="fa fa-home"></i></a> 				 
                 
                      </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="ModalLabel">New Project</h3>
                <div class="message" id="message"></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
        <form id="theForm" action="{{route('create_keys')}}" method="post">
            <div xclass="modal-body">

                <div class="col-xs-12 col-sm-6">
                 
                  <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                         
                        <div class="form-group">
                          <label for="title">Project Title</label>
                          <input type="text" name="title" id="title" value="" required class="form-control" />
                        </div>
                        <!--<div class="form-group">
                          <label for="private">Private Key<span class="small"></span></label>
                          <textarea  name="private" id="private"  required class="form-control"  >{{Auth::user()->accountNo}}</textarea>
                        </div>
                        <div class="form-group">
                          <label for="public">Public Key<span class="small"></span></label>
                          <textarea name="public" id="public" required class="form-control" >{{Auth::user()->accountNo}}</textarea>
                        </div>
                        --><div class="form-group">
                          <input type="hidden" name="accountNo" id="accountNo" value="{{Auth::user()->accountNo}}" class="form-control"  />
                        </div>
                      
               

              </div>
                
            </div>
            <div class="modal-footer">               
                <input type="submit" class="btn btn-primary" id="save" value="Generate" name="submit">
               
                <!--<button type="button" class="btn btn-warning" id="download" value="download" name="download">Download</button>-->
                <button type="button" class="btn btn-secondary" id="close" value="close" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
  </div>
 
  <!-- end modal-->

@stop