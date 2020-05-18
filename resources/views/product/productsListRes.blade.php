
@extends('restaurant.base')



@section('content')
{{App::setLocale(Session::get('locale'))}}



    <div class="ms-content-wrapper">
      <div class="row">

        <div class="col-md-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
              <li class="breadcrumb-item"><a href="#"><i class="material-icons">home</i> Home</a></li>
              <li class="breadcrumb-item"><a href="#">Stock Products</a></li>
              <li class="breadcrumb-item active" aria-current="page">Products List</li>

  
          
            </ol>
          </nav>


                  


          <div class="ms-panel">
            <div class="ms-panel-header">
              <h6>Products List</h6>
            </div>
            <div class="ms-panel-body">
              <div class="table-responsive">
                <table id="data-table-123" class="table w-100 thead-primary"></table>
              </div>
            </div>

            
          </div>







      
          









        </div>

      </div>
    </div>
  
  
@endsection




@section('script')

<script>

(function($) {
  'use strict';

   var dataSet12 = [
    @foreach ($products as $product)
                            

   [ "{{ $product->id }}","  {{ $product->productName }}",  "{{ $product->unity }}", "{{ $product->tax }} %", "{{ $product->limiteSTK }}", "{{ $product->qntSTKto }}"],
   
                            @endforeach
];









  var tableFour = $('#data-table-123').DataTable( {
    data: dataSet12,
    columns: [
      { title: "product ID" },
      { title: "product Name" },

      { title: "unity" },
      { title: "tax" },
      { title: "limite stock" },
      { title: "qnt in stock" },
   

    ],
  });


 




})(jQuery);

</script>

  
@endsection
