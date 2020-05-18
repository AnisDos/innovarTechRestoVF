<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Hash;
//use Image;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Meal;
use App\Product;
use App\Admin;
use App\Ingredient;
use Illuminate\Support\Arr;

use App\Restaurant;
use DB;
use Carbon\Carbon;


use Intervention\Image\ImageManagerStatic as Image;

class AdminController extends Controller
{
    

    public function __construct()
    {
        $this->middleware(['auth','isusernotconfirmed']);
    }


   // check if admin has privilage
   public function checkPrivilege(String $name)
   {
     
       $exists = Auth::user()->admin->privileges->contains($name);
   
       return $exists;
       if (!$exists) {
          
           return redirect()->back();
       }
   
       return true;
   
   }
   



    public function index()
    {
        $privileges = Auth::user()->admin->privileges()->get();
//partie chart===================================================================
$charts = array();
$restaurants = Auth::user()->admin->restaurants()->get();
// feach all restaurant of this admin and try yo evry one put there name end revuen of year
foreach($restaurants as $restaurant){
   $year = 2020;
   $tz = 'Europe/Madrid';
$mounth = array();
   for ($i=1; $i <13 ; $i++) { 
   $order = DB::table('orders')
   ->leftJoin('caisses',  'caisses.id', '=','orders.caisse_id')
   ->where('caisses.restaurant_id', $restaurant->id )
   ->where('orders.created_at', '>=', Carbon::createFromDate($year, $i,0, $tz) )
   ->where('orders.created_at', '<',Carbon::createFromDate($year, $i+1,0, $tz) )
   ->sum('orders.priceOrder');
   array_push($mounth,  $order);
}
   array_push($charts,  array( $restaurant->name,$mounth));
}
//end partie charts===================================================================
//partie chart===================================================================
$chartsexpenses = array();

// feach all restaurant of this admin and try yo evry one put there name end revuen of year
foreach($restaurants as $restaurant){
   $year = 2020;
   $tz = 'Europe/Madrid';
$mounth = array();
   for ($i=1; $i <13 ; $i++) { 
   $order = DB::table('charges')
   ->where('charges.restaurant_id', $restaurant->id )
   ->where('charges.created_at', '>=', Carbon::createFromDate($year, $i,0, $tz) )
   ->where('charges.created_at', '<',Carbon::createFromDate($year, $i+1,0, $tz) )
   ->sum('charges.priceCharge');
   array_push($mounth,  $order);
}
   array_push($chartsexpenses,  array( $restaurant->name,$mounth));
}
//end partie charts===================================================================
//dd($charts[0][1],$order);



        return view('admin.home',compact('chartsexpenses','charts','privileges') );

    }
 //=======================================================================================================
 //=======================================================================================================
 //=======================================================================================================
 //=======================================================================================================   
    public function addRestaurant()
    {

        $privileges = Auth::user()->admin->privileges()->get();
      /*   $meals = Meal::find(1);
        
        $oldingredients = $meals->ingredients()->get();
        dd($oldingredients);

        $meals = DB::table('meals')
        ->select('meals.*','categories.categoryName')
        ->leftJoin('categories',  'categories.id', '=','meals.category_id')
        ->leftJoin('users',  'users.id', '=','categories.user_id')
        ->where('users.id', 2)
        ->get();
      
      foreach($meals as $meal){
      
              $oldingredients = $meal->ingredients()->get();
              dd($oldingredients);
      }    */   
     
        return view('admin.addRestaurant', compact('privileges') );

    }
    

    public function addRestaurantFormValidation(Request $request)
{
   
    $data = request()->validate([
        'name' => 'required|min:3|max:50',
        'email' => 'unique:users',
        'password' => 'required|confirmed|min:6',
        'address'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->file('image')) {
        $imagePath = $request->file('image');
        $imageName = $imagePath->getClientOriginalName();

        $path = $request->file('image')->storeAs('users', $imageName, 'public');
      }
    
 /*    if (request('image') != null){
     
        $imagePath = request('image')->store('users','public');
       $image = Image::make(public_path("storage/{$imagePath}"))->fit(120,120);
       
        $image->save();

    } */

$password = \Hash::make($data['password']);


$compte = new User;
$compte->email = $data['email'];
$compte->password = $password;
$compte->save();
    //registre that employee delete version of product of this employee
    $restaurant = new Restaurant;
    $restaurant->name = $data['name'];
    $restaurant->address = $data['address'];
  /*   $restaurant->email = $data['email'];
    $restaurant->password = $password; */
   // if (request('image') != null){$restaurant->image = $imagePath;}
    if (request('image') != null){$restaurant->image = $path ;}
    
    $restaurant->admin()->associate(Auth::user()->admin);
    $restaurant->user()->associate($compte);
    $restaurant->save();

    //assign menus to new restaurant===========================================================================

    $categories = Auth::user()->admin->categories()->get();
   
  //  $categories = Category::where('user_id', Auth::user()->id)->get();
    $arrayReferencescatOldNew = array();
    foreach($categories as $category){

       $cat = $restaurant->categories()->create([
    
            'categoryName'=> $category->categoryName ,
           
            ]);

            // add in array key of category admin references to category restaurant
            
            $arrayReferencescatOldNew[$category->id] = $cat->id;
            
    

    }

    //dd($array);
//=======================================================================================================
// add product 

//nb: s3iba
//add only product not version productcuz evry restaurant have his own stock
//$products = Product::where('user_id',Auth::user()->id)->get();
$products = Auth::user()->admin->products()->get();
$arrayReferencesProductOldNew = array();
foreach($products as $product){

    
    $me = Product::create([
        
        'productName'=> $product->productName ,
        'unity'=>  $product->unity ,
        'limiteSTK'=> $product->limiteSTK ,
    
        ]);

        $me->restaurant()->associate($restaurant);
        $me->save();

        $arrayReferencesProductOldNew[$product->id] = $me->id;
            
    
   
          }


//=======================================================================================================

// add meals

//$meals = Meal::where('user_id',Auth::user()->id)->get();
//$meals == Auth::user()->categories()->meals();
  // $meals = Auth::user()->categories()->meals()->get();
         
  $meals =  DB::table('meals')
  ->select('meals.*')
  ->leftJoin('categories',  'categories.id', '=','meals.category_id')
  ->leftJoin('admins',  'admins.id', '=','categories.admin_id')
  ->where('admins.user_id', Auth::user()->id )
  ->get();

foreach($meals as $mealee){

    $meal = Meal::find($mealee->id);

    $category = Category::where('id',$arrayReferencescatOldNew[$meal->category_id])->first();
    $newmeal =    $category->meals()->create([
        
        'mealName'=> $meal->mealName ,
        'price'=>  $meal->price ,
        'description'=>  $meal->description ,
        'image'=>   $meal->image ,
        
    
        ]);

     /*    
        $newmeal->user()->associate($restaurant);
        $newmeal->save();

 */


        $oldingredients = $meal->ingredients()->get();


        foreach($oldingredients as $oldingredient){ 


        
            $product = Product::find($arrayReferencesProductOldNew[$oldingredient->product_id]);
           
           
            $ingredient = new Ingredient;
            $ingredient->qnt = $oldingredient->qnt;
            $ingredient->meal()->associate($newmeal);
            $ingredient->product()->associate($product);
            $ingredient->save();
    
    



        }



}

 
     
            
    return redirect()->back()->with("success","restaurant added with success !! Make some delicious food :)");

}


public function restaurantsList()
{

    $privileges = Auth::user()->admin->privileges()->get();

   // $restaurants= User::where('user_id',Auth::user()->id)->get();
    $restaurants= Auth::user()->admin->restaurants()->get();
    

    return view('admin.restaurantsList', compact('restaurants','privileges') );

}



public function restaurantDetails(Restaurant $restaurant)
{
 
    if ( !Restaurant::where('admin_id',auth::user()->admin->id)->where('id',$restaurant->id)->exists() ) {
        return redirect()->back()->with("danger"," please don't play with that ! Do your job seriously");
    }
 
$someInfoEmployees = $restaurant->employees()->get();


//partie chart===================================================================


$year = 2020;
$tz = 'Europe/Madrid';


$revenus = array();
   for ($i=1; $i <13 ; $i++) { 
   

   $order = DB::table('orders')
/*    ->select('orders.*') */
   ->leftJoin('caisses',  'caisses.id', '=','orders.caisse_id')
   ->where('caisses.restaurant_id', $restaurant->id )
   ->where('orders.created_at', '>=', Carbon::createFromDate($year, $i,0, $tz) )
   ->where('orders.created_at', '<',Carbon::createFromDate($year, $i+1,0, $tz) )
   ->sum('orders.priceOrder');



   array_push($revenus,  $order);



}

$depenses = array();
   for ($i=1; $i <13 ; $i++) { 
   

   $order = DB::table('charges')
/*    ->select('charges.*') */
   ->where('charges.restaurant_id', $restaurant->id )
   ->where('charges.created_at', '>=', Carbon::createFromDate($year, $i,0, $tz) )
   ->where('charges.created_at', '<',Carbon::createFromDate($year, $i+1,0, $tz) )
   ->sum('charges.priceCharge');



   array_push($depenses,  $order);



}


//end partie chart===================================================================



$totalrevenus= DB::table('orders')
   ->leftJoin('caisses',  'caisses.id', '=','orders.caisse_id')
   ->where('caisses.restaurant_id', $restaurant->id )
   ->where('orders.created_at', '>=',  Carbon::createFromDate($year, 1,0, $tz)  )
   ->where('orders.created_at', '<',Carbon::createFromDate($year+1, 1,0, $tz) )
   ->sum('orders.priceOrder');

$totaldepenses= DB::table('charges')
/*    ->select('charges.*') */
   ->where('charges.restaurant_id', $restaurant->id )
   ->where('charges.created_at', '>=', Carbon::createFromDate($year, 1,0, $tz) )
   ->where('charges.created_at', '<',Carbon::createFromDate($year+1, 1,0, $tz) )
   ->sum('charges.priceCharge');


   $totalorders =  DB::table('orders')
      ->leftJoin('caisses',  'caisses.id', '=','orders.caisse_id')
      ->where('caisses.restaurant_id', $restaurant->id )
      ->count('*');
   
   
   

//dd($totalrevenus,$totaldepenses);
$privileges = Auth::user()->admin->privileges()->get();

$charges = $restaurant->charges()->get();
$orders = DB::table('orders')
->select('orders.*')
->leftJoin('caisses',  'caisses.id', '=','orders.caisse_id')
->where('caisses.restaurant_id',$restaurant->id  )
->where('orders.orderStatus',"completed" )
->get();

$historyTransactions =$restaurant->transactionHistories()->get();


    return view('admin.restaurantDetails', compact('historyTransactions','orders','charges','restaurant','someInfoEmployees','revenus','depenses','totalrevenus','totaldepenses','totalorders','privileges') );
  

}





public function updateRestaurantInfo(Request $request){
    $restaurant = Restaurant::find(request()['id_res']);
   
    $data = request()->validate([
        'id_res'=> '',
        'name' => 'required|min:3|max:50',
        //'email' => 'unique:users'.request()['email'],
       // 'email'=>'',
       'email' => ['required','email', \Illuminate\Validation\Rule::unique('users')->ignore($restaurant->user->id)],
       // 'password' => 'confirmed|min:6',
        'address'=>'required',
        'image' => '',
    ]);

  
    if ($request->file('image')) {
        $imagePath = $request->file('image');
        $imageName = $imagePath->getClientOriginalName();

        $path = $request->file('image')->storeAs('users', $imageName, 'public');
      }
    
 
 /*    if (request('image') != null){
      
        $imagePath = request('image')->store('users','public');
        
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(120,120);
       
        $image->save();
       
    } */
  

$compte = User::find($restaurant->user->id);
$compte->email = $data['email'];
$compte->save();

    //$restaurant = User::find($data['id_res']);
    //dd($restaurant,$data['id_res']);
    $restaurant->name = $data['name'];
    $restaurant->address = $data['address'];
 
    if (request('image') != null){$restaurant->image = $path;}
    
    $restaurant->save();

    return redirect()->back()->with("success","restaurant information Updated with success !! ");


}







public function updatePasswordRestaurant()
{

    
    $data = request()->validate([
        'id_res'=> '',
        'Activate'=> '',
        'password' => 'confirmed|min:6',
       
    ]);

    $password = \Hash::make($data['password']);




    $restaurant = Restaurant::find($data['id_res']);



    
$compte = User::find($restaurant->user->id);
$compte->password = $password;
$compte->save();



    if (request('Activate') == "activih"){$restaurant->active = true;}
    
    $restaurant->save();
    return redirect()->back()->with("success","restaurant Password Updated with success !! ");


}

public function decativateRestaurant()
{

        
    $data = request()->validate([
        'id_res'=> '',

       
    ]);

    $password = "djit nkasi la routine";

    $restaurant = Restaurant::find($data['id_res']);


    
    
$compte = User::find($restaurant->user->id);
$compte->password = $password;
$compte->save();



   $restaurant->active = false;
    
    $restaurant->save();
    return redirect()->back()->with("danger","restaurant Blocked with success !! ");



}

//==========================================================================================================


public function addMeal()
{
    $this->checkPrivilege("stocks");
    $privileges = Auth::user()->admin->privileges()->get();
    
    
    
    //$categories = Category::where('user_id',Auth::user()->id)->get();
    //$products = Product::all();

    $categories = Auth::user()->admin->categories()->get();
    $products = Auth::user()->admin->products()->get();

    return view('admin.addMeal', compact('categories','products','privileges')  );

}







public function addMealForm(Request $request) 
{
    $data = request()->validate([
        'mealName' => 'required',
        'category' => 'required',
        'price' => ['required', 'max:10'],
        'description' => 'required',
        'tax' => 'required',
        'image' => 'required',
        'var'=>  '',
    ]);

 
    if ($request->file('image')) {
        $imagePath = $request->file('image');
        $imageName = $imagePath->getClientOriginalName();

        $path = $request->file('image')->storeAs('meals', $imageName, 'public');
      }
    
    
 /*    if (request('image')){

        $imagePath = request('image')->store('meals','public');
        
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(120,120);
       
        $image->save();
       
    } */

    $category = Category::find($data['category']);



$me =    $category->meals()->create([
    
         'mealName'=> $data['mealName'] ,
         'price'=>  $data['price'],
         'tax'=>  $data['tax'],
         'description'=>  $data['description'],
         'image'=>  $path,
         
     
         ]);

         
      /*    $me->user()->associate(Auth::user());
         $me->save(); */
         
     //put string geted from form too a array 
         $dataIngridient= preg_split("/[,]/",$data['var'][0]);
     


if (count($dataIngridient) > 1 ) {


for($i = 0;  $i< count($dataIngridient) ;  $i++)
{

   if($i % 2 == 0){ 

       $product = Product::find($dataIngridient[$i]);
       
       
       $ingredient = new Ingredient;
       $ingredient->qnt = $dataIngridient[$i+1];
       $ingredient->meal()->associate($me);
       $ingredient->product()->associate($product);
       $ingredient->save();


   }
                       
       
   

}


}

      


 
        
     return redirect()->back()->with("success"," Meal added with success !");

}





public function addCategory()
{
    $this->checkPrivilege("stocks");
    $privileges = Auth::user()->admin->privileges()->get();
    
    

    return view('admin.addCategory',compact('privileges') );

}







public function addCategoryForm() 
{
    $data = request()->validate([
        'categoryName' => 'required',
   
    ]);



$me =    Auth::user()->admin->categories()->create([
    
         'categoryName'=> $data['categoryName'] ,
    
     
         ]);
 

        
     return redirect()->back()->with("success"," Category added with success !");

}





public function mealsList()
{
    
    $this->checkPrivilege("stocks");
    $privileges = Auth::user()->admin->privileges()->get();
    
    //$meals = Meal::all();
   // $meals = Auth::user()->admin->categories()->meals()->get();
    $meals = DB::table('meals')
       ->select('meals.*')
       ->leftJoin('categories',  'categories.id', '=','meals.category_id')
       ->leftJoin('admins',  'admins.id', '=','categories.admin_id')
       ->where('admins.user_id', Auth::user()->id )
       ->get();

    //dd($meals);
    return view('admin.mealsList', compact('meals','privileges')  );

}





public function mealDetails(Meal $meal)
{

    $this->checkPrivilege("stocks");
    $privileges = Auth::user()->admin->privileges()->get();
    

    return view('admin.mealDetails', compact('meal','privileges')  );

}

public function updateMeal(Meal $meal)
{

    $this->checkPrivilege("stocks");
    $privileges = Auth::user()->admin->privileges()->get();
    
    
    $ingredients = Ingredient::where('meal_id', $meal->id)->get();
    $categories = Auth::user()->admin->categories()->get();
    //$categories = Category::where('user_id',Auth::user()->id)->get();
   // $products = Product::all();
    $products = Auth::user()->admin->products()->get();

    return view('admin.updateMeal', compact('meal','categories','products','ingredients','privileges')  );

}




public function updateMealForm(Request $request) 
{
    $data = request()->validate([
        'mealName' => 'required',
        'category' => 'required',
        'price' => ['required', 'max:10'],
        'description' => 'required',
        'image' => '',
        'var'=>  '',
        'id_meal'=>'',
    ]);

    
    if ($request->file('image')) {
        $imagePath = $request->file('image');
        $imageName = $imagePath->getClientOriginalName();

        $path = $request->file('image')->storeAs('meals', $imageName, 'public');
      }
    


  /*   
    if (request('image')){

        $imagePath = request('image')->store('meals','public');
        
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(120,120);
       
        $image->save();
       
    }
 */
    $category = Category::find($data['category']);



    $me = Meal::where('id',  $data['id_meal'])->first();


         $me->mealName=  $data['mealName'] ;
         $me->price=  $data['price'] ;
         $me->description=  $data['description'] ;
         if (request('image')){ $me->image=  $path ; }
         $me->category()->associate($category);
         
         $me->save();

    
         
     //put string geted from form too a array 
         $dataIngridient= preg_split("/[,]/",$data['var'][0]);


 //delete ingredient and recreate theme

$t=  Ingredient::where('meal_id', $me->id)->delete();



     //create ingridients of meal

     
     if (count($dataIngridient) > 1 ) {


        for($i = 0;  $i< count($dataIngridient) ;  $i++)
        {
    
           if($i % 2 == 0){ 
    
               $product = Product::find($dataIngridient[$i]);
               
               
               $ingredient = new Ingredient;
               $ingredient->qnt = $dataIngridient[$i+1];
               $ingredient->meal()->associate($me);
               $ingredient->product()->associate($product);
               $ingredient->save();
    
    
           }
                               
               
           
    
        }
    
    
    }


 
        
     return redirect()->back()->with("success"," Meal updated with success !");

}



//==============================================================================================
//==============================================================================================
//==============================================================================================
//==============================================================================================
//==============================================================================================
//==============================================================================================





public function chartTotalOrders()
{
    $privileges = Auth::user()->admin->privileges()->get();


    return view('admin.chartTotalOrders', compact('privileges')  );

}







public function allCustomers()
{
    

$this->checkPrivilege("customers");
$privileges = Auth::user()->admin->privileges()->get();


$tz = 'Europe/Madrid';
$year = carbon::now()->year;
$month = carbon::now()->month;


$customers = DB::table('orders')
->select('customers.*','restaurants.name as resname', DB::raw("SUM(orders.priceOrder) as priceIng") )
->leftJoin('customers',  'customers.id', '=','orders.customer_id')
->leftJoin('restaurants',  'restaurants.id', '=','customers.restaurant_id')
->leftJoin('admins',  'admins.id', '=','restaurants.admin_id')
->where('admins.id',Auth::user()->admin->id)
->where('orders.created_at', '>=', Carbon::createFromDate($year, $month,0, $tz) )
->where('orders.created_at', '<',Carbon::createFromDate($year, $month+1,0, $tz) )
->groupBy('orders.customer_id')
->get();


$allcustomers = DB::table('customers')
->select('customers.*','restaurants.name as resname')
->leftJoin('restaurants',  'restaurants.id', '=','customers.restaurant_id')
->leftJoin('admins',  'admins.id', '=','restaurants.admin_id')
->where('admins.id',Auth::user()->admin->id)
->groupBy('customers.id')
->get();




return view('admin.allCustomers',compact('allcustomers','customers','privileges',));


}



public function accountsettings(Admin $admin) 
{

    if(auth::user()->admin->id != $admin->id){

        return redirect()->back()->with("danger"," You try to do a danger things be careful I will punish you ! ");

    }
    $privileges = Auth::user()->admin->privileges()->get();



    return view('admin.accountsettings',compact('admin','privileges') );



}









public function updateadminInfo(Request $request){
    $data = request()->validate([
        'id_res'=> '',
        'name' => 'required|min:3|max:50',
        //'email' => 'unique:users'.request()['email'],
       // 'email'=>'',
       'email' => ['required','email', \Illuminate\Validation\Rule::unique('users')->ignore(request()['id_res'])],
       // 'password' => 'confirmed|min:6',
    
        'image' => '',
    ]);

    
    if ($request->file('image')) {
        $imagePath = $request->file('image');
        $imageName = $imagePath->getClientOriginalName();

        $path = $request->file('image')->storeAs('users', $imageName, 'public');
      }
    
  
 
   /*  if (request('image') != null){
      
        $imagePath = request('image')->store('users','public');
        
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(120,120);
       
        $image->save();
       
    } */
  



    $admin = Admin::find(Auth::user()->admin->id);
    $compte = User::find(Auth::user()->id);
    $admin->name = $data['name'];

    $compte->email = $data['email'];
    if (request('image') != null){$admin->image = $path;}
    
    $admin->save();
    $compte->save();

    return redirect()->back()->with("success","Admin information Updated with success !! ");


}


public function updatePasswordadmin()
{

    
    $data = request()->validate([
        'id_res'=> '',
        'Activate'=> '',
        'password' => 'confirmed|min:6',
       
    ]);

    $password = \Hash::make($data['password']);

    $compte = User::find($data['id_res']);
    $compte->password = $password;


    
    $compte->save();
    return redirect()->back()->with("success","Admin Password Updated with success !! ");


}













}
