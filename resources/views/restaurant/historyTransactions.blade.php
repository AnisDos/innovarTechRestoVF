
@extends('restaurant.base')



@section('content')
{{App::setLocale(Session::get('locale'))}}
<?php
use Carbon\Carbon;
?>



    <div class="ms-content-wrapper">
      <div class="row">

        <div class="col-md-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
              <li class="breadcrumb-item"><a href="#"><i class="material-icons">home</i> Home</a></li>
              <li class="breadcrumb-item"><a href="#">Menu</a></li>
              <li class="breadcrumb-item active" aria-current="page">History of Actions</li>

  
          
            </ol>
          </nav>


                  


          <div class="ms-panel">
            <div class="ms-panel-header">
            <h6>All Actions in your System  </h6>
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
    @foreach ($historyTransactions as $historyTransaction)
                            

   [ "{{ $historyTransaction->type }}","  {{ $historyTransaction->restaurant->name }}",  "@if( $historyTransaction->employee_id) {{ $historyTransaction->employee->idEmployee }} @endif", "{{ $historyTransaction->productVersion->product->productName }}","{{ $historyTransaction->oldqnt }}","{{ $historyTransaction->qnt }}" ,"{{ $historyTransaction->noteIfDelete }}" ,"{{ $historyTransaction->created_at }}"],
   
                            @endforeach
];









  var tableFour = $('#data-table-123').DataTable( {
    data: dataSet12,
    columns: [
      { title: "type" },
      { title: "restaurant name" },

      { title: "employee_id" },
      { title: "product_version_id" },
      { title: "oldqnt" },
      { title: "qnt" },
      { title: "noteIfDelete" },
      { title: "date" },
    
    


    ],
  });


 




})(jQuery);

</script>

  
@endsection
