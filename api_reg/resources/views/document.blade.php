@extends('layouts/blank')

@section('content')

<style>

#tabs{
	xbackground: #D9DEE4;
    color: #73879C;
}
#tabs h6.section-title{
    color: #73879C;
}

#tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #73879C;
    background-color: transparent;
    border-color: transparent transparent #73879C;
    border-bottom: 4px solid !important;
    font-size: 14px;
    font-weight: bold;
}
#tabs .nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    color: #73879C;
    font-size: 14px;
}
.tab-pane{
    padding:10px;
    min-height:200px
}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    xborder: 0px solid #2a40543d; 
    border-left: solid 1px #2a40543d!important;
    border-top: solid #2a40543d 1px !important;
}
.list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover {
    z-index: 2;
    color: #fff;
    background-color: #aeb1b3;
    border: 0px;
}

</style>
<script>
$( document ).ready(function() {
    $('.nav-tabs a[href="#nav-profile"]').tab('show');
    $('.nav-tabs a[href="#nav-desc"]').tab('show');
});


</script>

<div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div xclass="x_panel">
                  <div class="x_title ">
                    <h1>{ {{$post->title}} } Details</h1>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div xclass="x_content">
                    <p class="text-muted font-13 m-b-30">
                    <div id="loading"></div>
                     click on tabs below to view JSON
                                       
                                                         
                    <!-- Main  for a primary marketing message or call to action -->
                   

                    </div>
                        <div class="container">
                        <section id="tabs">
                       
	 
      
    <div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{$post->title}}  @if(Auth::user()->isAdmin())
                                          <a class="badge badge-secondary"  href="{{route('edit-doc',$post->id)}}"  > Edit <i class="fa fa-pencil"></i></a>
                                          
                                          @endif  </h3>					 
				</div>
               
				<div class="panel-body">
                <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Description</h5>
                    
                    </div> 
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                    
                    <p class="mb-1">{{$post->description}}</p>
                    </div> 
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Parameters 
                    
                    </h5>
                    
                    </div> 
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                    
                    <span>
                         <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Datatype</th>
                                <th scope="col">Required</th>
                                <th scope="col">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!$params->isEmpty())
                            @foreach ($params as $param)
                                <tr>
                                <th scope="row">{{$param->name}}</th>
                                <td>{{$param->datatype}}</td>
                                <td>{{$param->required}}</td>
                                <td>{{$param->description}}</td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                            </table>
                    </span> 
                   <p></p>
                    </div>
                    
                </a>
                </div>
                </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Request Response JSON Payload <a class="btn btn-primary"   href="{{route('guzzle',$post->id)}}"  > Try It <i class="fa fa-plus"></i></a></h3> 				 
				</div>
				<div class="panel-body">
                <div class="row">
			<div class="col-xs-12 ">
				<nav>
					<ul class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
						 <li><a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Request</a></li>
						<li><a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Response Success</a></li>
						<li><a class="nav-item nav-link" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-controls="nav-about" aria-selected="false">Response Error</a></li>
                         
                    </ul>
                     
				</nav>
               
				<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
					
					<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <pre><code>{{$post->request_body}}</code></pre>
                    </div>
					<div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <pre><code><span style=color:darkgreen>{{$post->response_success}}</span></code></pre>
                    </div>
					<div class="tab-pane fade" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                        <pre><code><span style=color:darkred>{{$post->response_error}}</span></code></pre>
                    </div>
				</div>
                
			</div>
	</div>
                </div><!--end of col-8-->
	</div><!--end row-->
	</div>
 
</section>
                          
                </div>
                 

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="ModalLabel">Edit Parameters {{$post->id}}</h3>
                <div class="message" id="message"></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
        @if(!$params->isEmpty())
        <form id="theForm" action="" method="post">
            <div xclass="modal-body">

                <div class="col-xs-12 col-sm-6">
                 
                  <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                 
                        <div class="form-group">
                          <label for="title">Name</label>
                          <input type="text" name="doc_id" id="doc_id" value="{{$param->doc_id}}" required class="form-control" />
                        
                          <input type="text" name="name" id="name" value="{{$param->name}}" required class="form-control" />
                        </div>
                        <div class="form-group">
                          <label for="datatype">Datatype<span class="small"></span></label>
                          <input type="text" name="datatype" id="datatype" value="{{$param->datatype}}" required class="form-control-sm"  />
                        </div>
                        <div class="form-group">
                          <label for="title">Required</label>
                          <input type="text" name="required" id="required" value="{{$param->required}}" required class="form-control-sm" />
                        </div>
                        <div class="form-group">
                          <label for="title">Description</label>
                          <input type="text" name="description" id="description" value="{{$param->description}}" required class="form-control" />
                        </div>
                      
               

              </div>
                
            </div>
            <div class="modal-footer">               
                <input type="submit" class="btn btn-primary" id="save" value="Save" name="submit"> 
                <!--<button type="button" class="btn btn-warning" id="download" value="download" name="download">Download</button>-->
                <button type="button" class="btn btn-secondary" id="createKeys" value="createKeys" data-dismiss="modal">Close</button>
            </div>
            </form>
        @endif
        </div>
    </div>
  </div>
 
  <!-- end modal-->

@stop