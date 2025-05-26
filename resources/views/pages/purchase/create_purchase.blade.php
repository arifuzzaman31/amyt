@extends('layout.app')
@section('title', 'Create Purchase | '.env('APP_NAME'))

@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <div class="layout-px-spacing">

                <div class="account-settings-container layout-top-spacing">
            
                  <div class="account-content">
                    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                      <div class="row">

                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                          <form id="edu-experience" class="section edu-experience">
                            <div class="info">
                              <h5 class="">Education</h5>
                              <div class="row">
                              
                                <div class="col-md-11 mx-auto">
            
                                  <div class="edu-section">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="degree1">Enter Your Collage Name</label>
                                          <input type="text" class="form-control mb-4" id="degree1"
                                            placeholder="Add your education here" value="Royal Collage of Art Designer Illustrator">
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label>Starting From</label>
            
                                              <div class="row">
            
                                                <div class="col-md-6">
                                                  <select class="form-control mb-4" id="s-from1">
                                                    <option>Month</option>
                                                    <option>Jan</option>
                                                    <option>Feb</option>
                                                    <option>Mar</option>
                                                    <option>Apr</option>
                                                    <option selected="selected">May</option>
                                                  
                                                    <option>Dec</option>
                                                  </select>
                                                </div>
            
                                                <div class="col-md-6">
                                                  <select class="form-control mb-4" id="s-from2">
                                                    <option>Year</option>
                                                    
                                                    <option>2010</option>
                                                    <option selected="selected">2009</option>
                                                    <option>2008</option>
                                                  
                                                    <option>1995</option>
                                                    <option>1994</option>
                                                    <option>1990</option>
                                                  </select>
                                                </div>
            
                                              </div>
            
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label>Ending In</label>
            
                                              <div class="row">
            
                                                <div class="col-md-6 mb-4">
                                                  <select class="form-control" id="end-in1">
                                                    <option>Month</option>
                                                    <option>Jan</option>
                                                  
                                                    <option>Nov</option>
                                                    <option>Dec</option>
                                                  </select>
                                                </div>
            
                                                <div class="col-md-6">
                                                  <select class="form-control input-sm" id="end-in2">
                                                    <option>Year</option>
                                                    <option>2020</option>
                                                    <option>2019</option>
                                                    <option>2018</option>
                                                    <option>2017</option>
                                                    
                                                    <option>1999</option>
                                                    <option>1998</option>
                                                    <option>1997</option>
                                                    <option>1996</option>
                                                    <option>1995</option>
                                                    <option>1994</option>
                                                    <option>1993</option>
                                                    <option>1992</option>
                                                    <option>1991</option>
                                                    <option>1990</option>
                                                  </select>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <textarea class="form-control" placeholder="Description" rows="10"></textarea>
                                      </div>
            
                                    </div>
            
                                  </div>
            
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
            
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                          <form id="work-experience" class="section work-experience">
                            <div class="info">
                              <h5 class="">Work Experience</h5>
                              <div class="row">
                                <div class="col-md-12 text-right mb-5">
                                  <button id="add-work-exp" class="btn btn-primary">Add</button>
                                </div>
            
                                  <div class="work-section">
                                 
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-4">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Date</th>
                                                        <th>Sale</th>
                                                        <th class="text-center">Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Shaun Park</td>
                                                        <td>10/08/2020</td>
                                                        <td>320</td>
                                                        <td class="text-center"><span class="text-success">Complete</span></td>
                                                        <td class="text-center"><svg> ... </svg></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alma Clarke</td>
                                                        <td>11/08/2020</td>
                                                        <td>420</td>
                                                        <td class="text-center"><span class="text-secondary">Pending</span></td>
                                                        <td class="text-center"><svg> ... </svg></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Xavier</td>
                                                        <td>12/08/2020</td>
                                                        <td>130</td>
                                                        <td class="text-center"><span class="text-info">In progress</span></td>
                                                        <td class="text-center"><svg> ... </svg></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Vincent Carpenter</td>
                                                        <td>13/08/2020</td>
                                                        <td>260</td>
                                                        <td class="text-center"><span class="text-danger">Canceled</span></td>
                                                        <td class="text-center"><svg> ... </svg></td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
            
              </div>
            {{-- <create-purchase /> --}}
        </div>
    </div>
</div>    
<!-- end modal -->
@endsection
@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/users/account-setting.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/dropify/dropify.min.css')}}">
@endpush
@push('script')
@vite(['resources/js/supplier.js'])
<script src="{{ asset('admin-assets/assets/js/users/account-settings.js')}}"></script>
<script src="{{ asset('admin-assets/plugins/dropify/dropify.min.css')}}"></script>
@endpush