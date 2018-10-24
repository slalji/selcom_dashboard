@extends('layouts/blank')

@section('content')

<style>
/*.col-sm-4 {
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
  white-space:normal !important;  
  overflow:visible;
  border: 1px solid #D9DEE4;
}*/
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
    font-size: 20px;
    font-weight: bold;
}
#tabs .nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    color: #73879C;
    font-size: 20px;
}
.tab-pane{
    padding:10px;
    min-height:200px
}

</style>
<script>
$( document ).ready(function() {
    $('#nav-home').attr('show',true);
});
</script>
 
<div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div xclass="x_panel">
                  <div class="x_title ">
                    
                  </div>
                  <div xclass="x_content">
                    <p class="text-muted font-13 m-b-30">
                    </p>
                    <!-- Main  for a primary marketing message or call to action -->
                   

                    </div>
                    @foreach ($posts as $post)
                        <div class="container">
                        <h1>Edit {{$post->title}}</h1>
        <form id="theForm" action="{{route('update-doc',$post->title)}}" method="post">              
    
        <div class="form-group row">
        
        <div class="col-sm-4">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
            {{Form::hidden('id', $post->id)}}
        </div>
        </div>
        <div class="form-group">
            {{Form::label('description', 'Description')}}
            {{Form::text('description', $post->description, ['class' => 'form-control', 'placeholder' => 'Description'])}}
         </div>
        <div class="form-group list-group-item list-group-item-action flex-column align-items-start active">
            {{Form::label('Parameters', 'Parameters')}}
            <h5 class="mb-1"> 
                    <span class="btn btn-info" data-toggle="modal" data-target="#myModal"  > Edit {{$post->title}} <i class="fa fa-pencil"></i></span>
                    <span class="btn btn-default" data-toggle="modal" data-target="#addModal"  > Add New Parameter <i class="fa fa-plus"></i></span>
                                          </h5>
             
                    </div>
        
        <div class="form-group">
            {{Form::label('request_body', 'Request')}}
            {{Form::textarea('request_body', $post->request_body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Request Payload'])}}
        </div>
        <div class="form-group">
            {{Form::label('response_success', 'Success')}}
            {{Form::textarea('response_success', $post->response_success, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Response Success Payload'])}}
        </div>
        <div class="form-group">
            {{Form::label('response_error', 'Error')}}
            {{Form::textarea('response_error', $post->response_error, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Response Success Payload'])}}
        </div>
        
        {{ csrf_field() }}
        <input type="submit" class="btn btn-primary" id="save" value="Save" name="submit"> 
                <!--<button type="button" class="btn btn-warning" id="download" value="download" name="download">Download</button>-->
                <button type="button" class="btn btn-secondary" id="home" value="home" data-dismiss="modal">Cancel</button>

        </form>
          @endforeach
                </div>
                 
 <!-- myModal -->
 
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="myModalLabel">Edit Parmaters</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <span>
      <form id="theForm" action="{{route('update-params',$post->id)}}" method="post">    
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
                 
                @foreach ($params as $param)
                    <tr>                     
                    <td>
                    {{Form::hidden('doc_id[]', $param->id, ['class' => 'form-control', 'placeholder' => 'id'])}}
                    {{Form::text('name[]', $param->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}</td>
                    <td>{{Form::text('datatype[]', $param->datatype, ['size' => '3','class' => 'form-control', 'placeholder' => 'Datatype'])}}</td>
                    <td>{{Form::text('required[]', $param->required, ['size' => '3','class' => 'form-control', 'placeholder' => 'Required'])}}</td>
                    <td>{{Form::text('description[]', $param->description, ['class' => 'form-control', 'placeholder' => 'Description'])}}</td>
                    </tr>
                @endforeach
               
                </tbody>
            </table>
        </span> 
      </div>
      <div class="modal-footer">
     
                    
      {{ csrf_field() }}
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Update">
        </form>
      </div>
    </div>
  </div>
</div>
     
   
 
  <!-- end modal-->
  <!-- add Modal -->
 
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="addModalLabel">Add New Parmater</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <span>
      <form id="theForm" action="{{route('add-params',$post->id)}}" method="post">    
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
                 
                
                    <tr>                     
                    <td>
                    {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}</td>
                    <td>{{Form::text('datatype', '', ['size' => '3','class' => 'form-control', 'placeholder' => 'Datatype'])}}</td>
                    <td>{{Form::text('required', '', ['size' => '3','class' => 'form-control', 'placeholder' => 'Required'])}}</td>
                    <td>{{Form::text('description', '', ['class' => 'form-control', 'placeholder' => 'Description'])}}</td>
                    </tr>
                
               
                </tbody>
            </table>
        </span> 
      </div>
      <div class="modal-footer">
     
                    
      {{ csrf_field() }}
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add">
        </form>
      </div>
    </div>
  </div>
</div>
     
   
 
  <!-- end modal-->

@stop
 