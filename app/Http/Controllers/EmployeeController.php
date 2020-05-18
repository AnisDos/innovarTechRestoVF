<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Hash;
use App\Product;
use App\ProductVersion;
use App\provider;
use App\TransactionHistory;

use Illuminate\Support\Facades\DB;



class EmployeeController extends Controller
{
    

        
    public function __construct()
    {
        $this->middleware(['auth','isusernotconfirmedEmployee']);
    }


    
    public function index()
    {
        // dd(Auth::user()->email,Auth::user()->idEmployee);

        $privileges = Auth::user()->employee->privileges()->get();
        //dd($privileges);

        return view('employee.home',compact('privileges'));

    }


    //  tomatich =======> 1
    //  stock =======> 2
    //  stocks =======> 3


// check if employee has privilage
public function checkPrivilege(String $name)
{
  
    $exists = Auth::user()->employee->privileges->contains($name);

    return $exists;
    if (!$exists) {
       
        return redirect()->back();
    }

    return true;

}

//==================================================== stocks ============================================


public function stocks()
{
    $this->checkPrivilege("stocks");
       
   
    return view('employee.stocks');

}

public function stocksUpdateQnttToProduct()
{
    $this->checkPrivilege("stocks");

    $privileges = Auth::user()->employee->privileges()->get();
    
//this requet is not 100% correct check it again please
//get all version of product of
//  products.id as prod_id product_versions.id as vers_id product_versions.product_id product_versions.provider_id product_versions.price product_versions.return product_versions.date_experation_bool product_versions.date_experation product_versions.qntSTK product_versions.codebare product_versions.created_at product_versions.updated_at products.user_id products.productName products.unity products.limiteSTK
 
   $products = DB::select("select *  from products  LEFT JOIN product_versions ON product_versions.product_id = products.id
   where  products.restaurant_id =  " . Auth::user()->employee->restaurant_id ." " );

//this loop for testing
    /*     $stack=[];
    foreach ($products as $product){
        array_push($stack, $product);
    }
    dd($stack);
                   */          

   
    return view('employee.UpdateQnttToProduct', compact('products','privileges'));

}

public function addQntProduct()
{
    $this->checkPrivilege("stocks");

    $data = request()->validate([
        'id_product' => 'required',
        'qntToAdd' => 'required',
      
    ]);

    //add qnt to the product(update qntt)
    $productVersion = ProductVersion::find($data['id_product']);
    $oldqnt = $productVersion->qntSTK ;
    $productVersion->qntSTK = $productVersion->qntSTK + $data['qntToAdd'];
    $productVersion->save();

    //registre the transation of this employee
         
    $transactionHistory = new TransactionHistory;
    $transactionHistory->oldqnt = $oldqnt;
    $transactionHistory->qnt = $data['qntToAdd'];
    $transactionHistory->type = "addqnt";
    $transactionHistory->restaurant()->associate(Auth::user()->employee->restaurant);
    $transactionHistory->employee()->associate(Auth::user()->employee);
    $transactionHistory->productVersion()->associate($productVersion);
    $transactionHistory->save();


 
     
            
    return redirect()->back()->with("success","Quentity of Product updated with success !");

}


public function revokeQntProduct()
{
    
    
    $data = request()->validate([
        'id_product' => 'required',
        'qntToAdd' => 'required',
      
    ]);

    //revoke qnt from the product(update qntt)
    $productVersion = ProductVersion::find($data['id_product']);
    $oldqnt = $productVersion->qntSTK ;
    $productVersion->qntSTK = $productVersion->qntSTK - $data['qntToAdd'];
    $productVersion->save();

    //registre the transation of this employee
         
    $transactionHistory = new TransactionHistory;
    $transactionHistory->oldqnt = $oldqnt;
    $transactionHistory->qnt = $data['qntToAdd'];
    $transactionHistory->type = "revokeqnt";
    $transactionHistory->restaurant()->associate(Auth::user()->employee->restaurant);
    $transactionHistory->employee()->associate(Auth::user()->employee);
    $transactionHistory->productVersion()->associate($productVersion);
    $transactionHistory->save();


 
     
            
    return redirect()->back()->with("success","Quentity of Product updated with success !");

}



public function DeleteVersionProduct()
{
    
    
    $data = request()->validate([
        'id_product' => 'required',
        'textDelete' => 'required',
      
    ]);

    //delete version of product
    
    $pr = ProductVersion::find($data['id_product']);
    $pro = Product::find( $pr->product_id);
    $text = "employe has delete version of : " . $pro->productName  . " . his note for that is: "  . $data['textDelete'];

    $productVersion = ProductVersion::find($data['id_product'])->delete();

    //registre that employee delete version of product of this employee
         
    $transactionHistory = new TransactionHistory;
    $transactionHistory->type = "delete";
    $transactionHistory->noteIfDelete =$text ;
    $transactionHistory->restaurant()->associate(Auth::user()->employee->restaurant);
    $transactionHistory->employee()->associate(Auth::user()->employee);
    $transactionHistory->save();


 
     
            
    return redirect()->back()->with("success","Quentity of Product updated with success !");

}

public function stocksversionProduct()
{
    $this->checkPrivilege("stocks");
           
        $privileges = Auth::user()->employee->privileges()->get();
  /*   $products = DB::select("select *  from products  LEFT JOIN product_versions ON product_versions.product_id = products.id
    where  products.user_id =  " . Auth::user()->user_id ." " );
     */
  /*   $products = Product::where('user_id',Auth::user()->user_id)->get();
    
    $providers = Provider::where('user_id',Auth::user()->user_id)->get(); */

    $products = Auth::user()->employee->restaurant->products()->get();
    $providers = Auth::user()->employee->restaurant->providers()->get();
   
    return view('product.stocksversionProduct', compact('products','providers','privileges'));

}

public function addVersionProductForm() 
{
    $data = request()->validate([
        'id_product' => 'required',
        'qntSTK' => 'required',
        'price' => 'required',
        'return' => '',
        'date_experation_bool' => '',
        'date_experation' => '',
        'codebare' => 'required',
        'provider_id' => '',
      
    ]);

 

    if (empty($data['date_experation_bool'])){
        $date_experation_bool=false;
        
                }else {
                    $date_experation_bool=true;
                }


                if (empty($data['return'])){
                    $return=false;
                    
                            }else {
                                $return=true;
                            }         


              



    if ($date_experation_bool) {



        $me = Product::find($data['id_product']);

            $version = $me->productVersions()->create([
                
            'qntSTK'=>  $data['qntSTK'],
            'price'=>  $data['price'],
            'return'=>  $return,
            'date_experation_bool'=>  $date_experation_bool,
            'date_experation'=>  $data['date_experation'],
            'codebare'=>  $data['codebare'],
            ]);

            $provider = Provider::find($data['provider_id']);

            if ($provider) 
            { 
            $version->provider()->associate($provider);
            }

        
    }else{
        $me = Product::find($data['id_product']);


            $version = $me->productVersions()->create([
                
            'qntSTK'=>  $data['qntSTK'],
            'price'=>  $data['price'],
            'return'=>  $return,
            'date_experation_bool'=>  $date_experation_bool,
            'codebare'=>  $data['codebare'],
            ]);

            $provider = Provider::find($data['provider_id']);

            if ($provider) 
            { 
            $version->provider()->associate($provider);
            }


    }



    

      
    //registre the transation of this employee adding version 
         
    $transactionHistory = new TransactionHistory;
    $transactionHistory->qnt = $data['qntSTK'];
    $transactionHistory->type = "addnew";
    $transactionHistory->restaurant()->associate(Auth::user()->employee->restaurant);
    $transactionHistory->employee()->associate(Auth::user()->employee);
    $transactionHistory->productVersion()->associate($version);
    $transactionHistory->save();

 //add charge  mazalha f test

 $charge = new Charge;
 $charge->priceCharge = $data['price'];
 $charge->type = "stock";
 $charge->note = "employee added new version of product";
 $charge->employee()->associate(Auth::user()->employee);
 $charge->restaurant()->associate(Auth::user()->employee->restaurant);
 $charge->save();


        
     return redirect()->back()->with("success"," Product added with success !! you can check it :)");

}
//==================================================== end stocks ============================================
public function tomatich()
{
    
    return view('employee.tomatich');

}
public function caisse()
{
    
    return view('employee.caisse');

}





}
