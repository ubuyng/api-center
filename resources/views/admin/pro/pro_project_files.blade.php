@extends('layouts.mvp_dash3')

@section('page-css')


<link rel="stylesheet" href="{{ asset('/mvp_ui/vendors/dropzone/dropzone.css') }}" rel="stylesheet">

    <style>

.box
				{
					font-size: 1.25rem; /* 20 */
					background-color: #c8dadf;
					position: relative;
					padding: 100px 20px;
				}
				.box.has-advanced-upload
				{
					outline: 2px dashed #92b0b3;
					outline-offset: -10px;

					-webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
					transition: outline-offset .15s ease-in-out, background-color .15s linear;
				}
				.box.is-dragover
				{
					outline-offset: -20px;
					outline-color: #c8dadf;
					background-color: #fff;
				}
					.box__dragndrop,
					.box__icon
					{
						display: none;
					}
					.box.has-advanced-upload .box__dragndrop
					{
						display: inline;
					}
					.box.has-advanced-upload .box__icon
					{
						width: 100%;
						height: 80px;
						fill: #92b0b3;
						display: block;
						margin-bottom: 40px;
					}

					.box.is-uploading .box__input,
					.box.is-success .box__input,
					.box.is-error .box__input
					{
						visibility: hidden;
					}

					.box__uploading,
					.box__success,
					.box__error
					{
						display: none;
					}
					.box.is-uploading .box__uploading,
					.box.is-success .box__success,
					.box.is-error .box__error
					{
						display: block;
						position: absolute;
						top: 50%;
						right: 0;
						left: 0;

						-webkit-transform: translateY( -50% );
						transform: translateY( -50% );
					}
					.box__uploading
					{
						font-style: italic;
					}
					.box__success
					{
						-webkit-animation: appear-from-inside .25s ease-in-out;
						animation: appear-from-inside .25s ease-in-out;
					}
						@-webkit-keyframes appear-from-inside
						{
							from	{ -webkit-transform: translateY( -50% ) scale( 0 ); }
							75%		{ -webkit-transform: translateY( -50% ) scale( 1.1 ); }
							to		{ -webkit-transform: translateY( -50% ) scale( 1 ); }
						}
						@keyframes appear-from-inside
						{
							from	{ transform: translateY( -50% ) scale( 0 ); }
							75%		{ transform: translateY( -50% ) scale( 1.1 ); }
							to		{ transform: translateY( -50% ) scale( 1 ); }
						}

					.box__restart
					{
						font-weight: 700;
					}
					.box__restart:focus,
					.box__restart:hover
					{
						color: #39bfd3;
					}

					.js .box__file
					{
						width: 0.1px;
						height: 0.1px;
						opacity: 0;
						overflow: hidden;
						position: absolute;
						z-index: -1;
					}
					.js .box__file + label
					{
						max-width: 80%;
						text-overflow: ellipsis;
						white-space: nowrap;
						cursor: pointer;
						display: inline-block;
						overflow: hidden;
					}
					.js .box__file + label:hover strong,
					.box__file:focus + label strong,
					.box__file.has-focus + label strong
					{
						color: #39bfd3;
					}
					.js .box__file:focus + label,
					.js .box__file.has-focus + label
					{
						outline: 1px dotted #000;
						outline: -webkit-focus-ring-color auto 5px;
					}
						.js .box__file + label *
						{
							/* pointer-events: none; */ /* in case of FastClick lib use */
						}

					.no-js .box__file + label
					{
						display: none;
					}

					.no-js .box__button
					{
						display: block;
					}
					.box__button
					{
						font-weight: 700;
						color: #e5edf1;
						background-color: #39bfd3;
						display: none;
						padding: 8px 16px;
						margin: 40px auto 0;
					}
						.box__button:hover,
						.box__button:focus
						{
							background-color: #0f3c4b;
						}
img.file_zone_img {
    max-width: 32%;
}
.attachment-box{
  flex: 0 1 calc(25% - 21px);
}
    </style>
@endsection

@section('content')
 
<div class="clearfix"></div>

      
<div class="container">
    <div class="row">
            <div class="col-xl-8">
                  
	<form method="post" action="{{route('pro_save_project_file')}}" enctype="multipart/form-data" novalidate class="box">

		
		<div class="box__input">
			<svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
			<input type="file" style="display:none" name="files" id="file" class="box__file" data-multiple-caption="{count} files selected" multiple />
      @csrf
    <input type="hidden" name="project_id" value="{{$project->id}}">
    <input type="hidden" name="bid_id" value="{{$bid->id}}">
    <input type="hidden" name="pro_id" value="{{Auth::User()->id}}">
    <input type="hidden" name="cus_id" value="{{$customer->id}}">
    <input type="hidden" name="project_name" value="{{$project->sub_category_name}}">
    <input type="hidden" name="sender_name" value="{{$pro->business_name}}">
      <label for="file"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>
			<button type="submit" class="box__button">Upload</button>
		</div>

		 
		<div class="box__uploading">Uploading&hellip;</div>
	</form>

  <br>
  <div class="attachments-container" id="files">
   <h4><mark class="color"> Loading files </mark></h4>
    </div>
</div>
              <!-- Sidebar -->
		<div class="col-xl-4 col-lg-4">
                <div class="sidebar-container">
                    <a href="{{ str_replace(url('/'), '', url()->previous())}}" class="apply-now-button popup-with-zoom-anim"> <i class="icon-material-outline-arrow-back"></i> Back </a>
                        
                    <!-- Sidebar Widget -->
                    <div class="sidebar-widget">
                        <div class="job-overview">
                            <div class="job-overview-headline">Project Summary</div>
                            <div class="job-overview-inner">
                                <ul>
                                    <li>
                                        <i class="icon-material-outline-location-on"></i>
                                        <span>Location</span>
                                        @if($user->isOnline())
                                            user is online!!
                                        @endif
                                        <h5>{{$project->city}} - {{$project->state}}</h5>
                                    </li>
                                    <li>
                                        <i class="icon-material-outline-business-center"></i>
                                        <span>Project Name</span>
                                        <h5>{{$project->sub_category_name}}</h5>
                                    </li>
                                    <li>
                                        <i class="icon-material-outline-access-time"></i>
                                        <span>Date Posted</span>
                                        <h5>{{$project->created_at->diffForHumans()}}</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      
    </div>
</div>

<div class="clearfix"></div>


    @section('page-js') 
    <script>
$.get("/dashboard/inbox/ajax/project/files/{{$project->id}}", function(data){
                          // Display the returned data in browser
                          $("#files").html(data);
                      });
        'use strict';
      
        ;( function( $, window, document, undefined )
        {
          // feature detection for drag&drop upload
      
          var isAdvancedUpload = function()
            {
              var div = document.createElement( 'div' );
              return ( ( 'draggable' in div ) || ( 'ondragstart' in div && 'ondrop' in div ) ) && 'FormData' in window && 'FileReader' in window;
            }();
      
      
          // applying the effect for every form
      
          $( '.box' ).each( function()
          {
            var $form		 = $( this ),
              $input		 = $form.find( 'input[type="file"]' ),
              $label		 = $form.find( 'label' ),
              $errorMsg	 = $form.find( '.box__error span' ),
              $restart	 = $form.find( '.box__restart' ),
              droppedFiles = false,
              showFiles	 = function( files )
              {
                $label.text( files.length > 1 ? ( $input.attr( 'data-multiple-caption' ) || '' ).replace( '{count}', files.length ) : files[ 0 ].name );
              };
      
            // letting the server side to know we are going to make an Ajax request
            $form.append( '<input type="hidden" name="ajax" value="1" />' );
      
            // automatically submit the form on file select
            $input.on( 'change', function( e )
            {
              showFiles( e.target.files );
      
              
              $form.trigger( 'submit' );
      
              
            });
      
      
            // drag&drop files if the feature is available
            if( isAdvancedUpload )
            {
              $form
              .addClass( 'has-advanced-upload' ) // letting the CSS part to know drag&drop is supported by the browser
              .on( 'drag dragstart dragend dragover dragenter dragleave drop', function( e )
              {
                // preventing the unwanted behaviours
                e.preventDefault();
                e.stopPropagation();
              })
              .on( 'dragover dragenter', function() //
              {
                $form.addClass( 'is-dragover' );
              })
              .on( 'dragleave dragend drop', function()
              {
                $form.removeClass( 'is-dragover' );
              })
              .on( 'drop', function( e )
              {
                droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
                showFiles( droppedFiles );
      
                
                $form.trigger( 'submit' ); // automatically submit the form on file drop
      
                
              });
            }
      
      
            // if the form was submitted
      
            $form.on( 'submit', function( e )
            {
              // preventing the duplicate submissions if the current one is in progress
              if( $form.hasClass( 'is-uploading' ) ) return false;
      
              $form.addClass( 'is-uploading' ).removeClass( 'is-error' );
      
              if( isAdvancedUpload ) // ajax file upload for modern browsers
              {
                e.preventDefault();
      
                // gathering the form data
                var ajaxData = new FormData( $form.get( 0 ) );
                if( droppedFiles )
                {
                  $.each( droppedFiles, function( i, file )
                  {
                    ajaxData.append( $input.attr( 'name' ), file );
                  });
                }
      
                // ajax request
                $.ajax(
                {
                  url: 			$form.attr( 'action' ),
                  type:			$form.attr( 'method' ),
                  data: 			ajaxData,
                  dataType:		'json',
                  cache:			false,
                  contentType:	false,
                  processData:	false,
                  complete: function()
                  {
                    $form.removeClass( 'is-uploading' );
                  },
                                    success: function( data )
                        {
                      if(data=='invalid')
                      {
                          Snackbar.show({
                      text: 'File not supported!',
                      pos: 'top-center',
                      showAction: false,
                      actionText: "Dismiss",
                      duration: 1000,
                      textColor: '#fff',
                          dismiss:false,
                      backgroundColor: '#383838'
                    }); 
                    $form.addClass( data.success == true ? 'is-success' : 'is-error' );
                    if( !data.success ) $errorMsg.text( data.error );
                    
                      }
                      else{
                          Snackbar.show({
                      text: 'file uploaded',
                      pos: 'top-center',
                      showAction: false,
                      actionText: "Dismiss",
                      duration: 3000,
                      textColor: '#fff',
                          dismiss:false,
                      backgroundColor: '#383838'
                    }); 
                      }
                        },
                      error: function(e) 
                        {
                          if(e.responseText=='success'){
                            Snackbar.show({
                      text: 'file uploaded',
                      pos: 'top-center',
                      showAction: false,
                      actionText: "Dismiss",
                      duration: 3000,
                      textColor: '#fff',
                          dismiss:false,
                      backgroundColor: '#383838'
                    }); 

                    $.get("/dashboard/inbox/ajax/project/files/{{$project->id}}", function(data){
                          // Display the returned data in browser
                          $("#files").html(data);
                      });
                          } else{
                            Snackbar.show({
                      text: 'File not supported!',
                      pos: 'top-center',
                      showAction: false,
                      actionText: "Dismiss",
                      duration: 1000,
                      textColor: '#fff',
                          dismiss:false,
                      backgroundColor: '#383838'
                    }); 
                          }
                        }          
               
               
                });
              }
              else // fallback Ajax solution upload for older browsers
              {
                var iframeName	= 'uploadiframe' + new Date().getTime(),
                  $iframe		= $( '<iframe name="' + iframeName + '" style="display: none;"></iframe>' );
      
                $( 'body' ).append( $iframe );
                $form.attr( 'target', iframeName );
      
                $iframe.one( 'load', function()
                {
                  var data = $.parseJSON( $iframe.contents().find( 'body' ).text() );
                  $form.removeClass( 'is-uploading' ).addClass( data.success == true ? 'is-success' : 'is-error' ).removeAttr( 'target' );
                  if( !data.success ) $errorMsg.text( data.error );
                  $iframe.remove();
                });
              }
            });
      
      
            // restart the form if has a state of error/success
      
            $restart.on( 'click', function( e )
            {
              e.preventDefault();
              $form.removeClass( 'is-error is-success' );
              $input.trigger( 'click' );
            });
      
            // Firefox focus bug fix for file input
            $input
            .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
            .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
          });
      
        })( jQuery, window, document );
      
      </script>

    @endsection
@endsection