@extends('layout.app')
@section('title', 'Attribute | Aranya')

@push('style')
<link href="{{ asset('admin-assets/plugins/animate/animate.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin-assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/forms/switches.css')}}">
@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 25px 0;">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                    <h4>Attribute</h4>
                    <!-- <a class="btn btn-outline-primary" href="{{ route('add-category') }}">Add New</a> -->
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" id="addAttr" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder-plus"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg> Add New
                        </button>
                    </div>
                </div>                 
            </div>
        </div>

        <div class="row layout-top-spacing" id="cancel-row">
                
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table id="attrTable" data-orderable="true" class="table table-bordered table-hover table-striped mb-4" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Attribute</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    


<!-- Modal -->
<div id="creatAttrModal" class="modal animated fadeInRight custo-fadeInRight show" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form  method="POST" enctype="multipart/form-data" id="add-attr-form">
            <div class="modal-header">
                <h5 class="modal-title" id="attributeModalLabel">Create Attribute</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                        <div class="statbox box box-shadow" style="position: relative;border: 1px solid #00000021;background: #fff;">
                            <div class="widget-content widget-content-area">
                                    <div class="form-group">
                                        <label for="attr_name">Attribute Name</label>
                                        <input type="text" class="form-control" name="attribute_name" id="attr_name" placeholder="Attribute Name">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="country_of_origin">Country Of Origin</label>
                                        <input type="text" class="form-control" name="country_of_origin" id="country_of_origin" placeholder="Country Of Origin">
                                    </div>
                                  
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                                    <label for="cat-status">Status</label>
                                            <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                                <input name="status" type="checkbox" checked="" id="cat-status">
                                                <span class="slider round"></span>
                                            </label>
                                    </div>
                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="submit" id="modal-button" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal -->
@endsection

@push('script')
<script>
    $(document).ready(function(){
 
        var table = $('#attrTable').DataTable({
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            order: [0,'desc'],
            ajax: "{{ url('admin/get-attribute-data') }}",
            columns: [
                {data : 'id'},
                {data : 'name'},
                {data : 'description'},
                {
                    data: 'status',
                    render: function (data, type, row) {
                            // console.log(data)
                        if (data === 1) {
                            return "<p class=\"text-primary\" > Active</p>"
                        } else {
                            return "<p class=\"text-warning\"> Inactive</p>"
                        }
 
                    }
 
                },
                {   
                    targets: -1,
                    data: null,
                    "defaultContent": "null", "render": function(data,type,row,meta) {
                        return '<button ' + 'class="edit btn btn-warning"' +'type="button"' + 'value="Edit"'+'>'+ 
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></button>'+
                        '<button ' + 'class="delete btn btn-danger ml-2"' +'type="button"' + 'value="Delete"'+'>'+ '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>';
                    },
                }
            ],
            
        });


        $('#attrTable tbody').on('click', 'button', function () {
                var action = this.className; 
                var tr = table.row( $(this).parents('tr') )
                var data = tr.data();
                removeErrorMsg()
                if(this.value == 'Edit'){
                    let urldata = "{{ url('admin/attribute-update') }}"+'/'+ data.id;
                    document.getElementById("add-attr-form").setAttribute('data-action',urldata);
                    $("#attributeModalLabel").text('Update Attribute');
                    $("#modal-button").text('Update')
                    document.forms['add-attr-form']['attr_name'].value = data.name;
                    document.forms['add-attr-form']['description'].value = data.description;
                    document.forms['add-attr-form']['country_of_origin'].value = data.country_of_origin;
                    document.forms['add-attr-form']['cat-status'].checked = data.status;
                    $('#creatAttrModal').modal('show');
                } else {
                    deleteData(tr) 
                }
        });

        var form = '#add-attr-form';

        $(form).on('submit', function(event){
            event.preventDefault();
            
            var url = $(this).attr('data-action');

            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    if($.isEmptyObject(response.error)){
                        alertMessage(response)
                        $(form).trigger("reset");
                        $('#creatAttrModal').modal('hide');
                    }else{
                        printErrorMsg(response.error);
                    }

                },
                error: function(response) {
                    console.log(response)
                }
            });
        });

        // function printErrorMsg (msg) {
        //     $(".print-error-msg").find("ul").html('');
        //     $(".print-error-msg").css('display','block');
        //     $.each( msg, function( key, value ) {
        //         $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        //     });
        // }

        // function alertMessage(data){
        //     const toast = swal.mixin({
        //         toast: true,
        //         position: 'top-end',
        //         showConfirmButton: false,
        //         timer: 2000,
        //         padding: '2em'
        //     });

        //     toast({
        //         type: data.status,
        //         title: data.message,
        //         padding: '2em',
        //     })
        // }

        document.getElementById("addAttr").addEventListener("click", addAttrModal);

            function addAttrModal() {
                removeErrorMsg()
                let urldata = "{{ route('attribute.store') }}"
                document.getElementById("add-attr-form").setAttribute('data-action',urldata);
                $("#attributeModalLabel").text('Add Attribute');
                $("#modal-button").text('Save')
                $("#add-attr-form").trigger("reset");
                $('#creatAttrModal').modal('show');
            }

        function deleteData(data){
            const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success btn-rounded',
                cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
                buttonsStyling: false,
            })

            swalWithBootstrapButtons({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('admin/attribute/destroy') }}"+'/'+data.data().id,
                        method: 'DELETE',
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(response)
                        {
                            console.log(response)
                            if($.isEmptyObject(response.error)){
                                swalWithBootstrapButtons(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                                // location.reload()
                                data.remove().draw();
                            }else{
                                printErrorMsg(response.error);
                            }

                        },
                        error: function(response) {
                            console.log(response)
                        }
                    });
                    
                } else if (
                // Read more about handling dismissals
                result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            })
        }

    });
</script>

@endpush