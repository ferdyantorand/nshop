<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 15/09/2017
 * Time: 9:59
 */

namespace App\libs;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Collection;

class Zoho
{
    /**
     * Function to get Token from Zoho Inventory.
     */
    public static function getToken()
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_AUTH_BASE_URL')
            ]);

            $request = $client->request('POST', env('ZOHO_AUTH_BASE_URL'), [
                'form_params' => [
                    'SCOPE' => 'ZohoInventory/inventoryapi',
                    'EMAIL_ID' => env('ZOHO_EMAIL'),
                    'PASSWORD' => env('ZOHO_PASSWORD')
                ]
            ]);


            if($request->getStatusCode() == 200){
                $collect = $request->getBody();
                //Should Split the Token
                $temp = explode("=", $collect);
                $result = explode(" ", $temp[1]);
                $configuration = Configuration::where('configuration_key', 'zoho_token')->first();
                $configuration->zoho_token = $result[0];
                $configuration->save();

                return true;
            }
            else{
                return false;
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > createUser ".$ex);
            return false;
        }
    }

    //User Stuff Start
    /**
     * Function to save Contact to Zoho Inventory. in Our case it is User.
     *
     * @param User $user
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createUser(User $user)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $testJson = [
                'contact_name'  => $user->first_name . ' ' . $user->last_name,
                'website'       => 'www.nama-official.com',
                'billing_address'   => [
                    'attention' => $user->first_name . ' ' . $user->last_name,
                    'address'   => "default",
                    'street2'   => "default",
                    'city'      => "default",
                    'state'     => "default",
                    'zip'       => 111111,
                    'country'   => "Indonesia"
                ],
                'shipping_address'  => [
                    'attention' => $user->first_name . ' ' . $user->last_name,
                    'address'   => "default",
                    'street2'   => "default",
                    'city'      => "default",
                    'state'     => "default",
                    'zip'       => 111111,
                    'country'   => "Indonesia"
                ],
                'contact_persons'   => [[
                    'salutation'    => 'Mr',
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'email'         => $user->email,
                    'phone'         => $user->phone,
                    'mobile'        => $user->phone,
                    'is_primary_contact'    => true
                ]]
            ];

            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();
            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'contacts?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($testJson)
                ]
            ]);

            if($request->getStatusCode() == 200 || $request->getStatusCode() == 201){
                $collect = json_decode($request->getBody());

                //Save Contact Id
                $userData = User::find($user->id);
                $userData->zoho_id = $collect->contact->contact_id;
                $userData->zoho_primary_contact_id = $collect->contact->primary_contact_id;
                $userData->save();

                return $collect;
            }
            else{
                return "Error!";
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > createUser ".$ex);
            return "Error!";
        }
    }

    /**
     * Function to update user Data.
     *
     * @param User $user
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function updateUser(User $user)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $jsonData = [
                'contact_name'  => $user->first_name . ' ' . $user->last_name,
                'company_name'  => 'Nama-Official',
                'payment_terms' => 1,
                'currency_id'   => env('ZOHO_CURRENCY_ID'),
                'website'       => 'www.nama-official.com',
                'billing_address'   => [
                    'attention' => $user->first_name . ' ' . $user->last_name,
                    'address'   => $user->addresses[0]->description,
                    'street2'   => $user->addresses[0]->street,
                    'city'      => $user->addresses[0]->city->name,
                    'state'     => $user->addresses[0]->state,
                    'zip'       => $user->addresses[0]->postal_code,
                    'country'   => "Indonesia"
                ],
                'shipping_address'  => [
                    'attention' => $user->first_name . ' ' . $user->last_name,
                    'address'   => $user->addresses[0]->description,
                    'street2'   => $user->addresses[0]->street,
                    'city'      => $user->addresses[0]->city->name,
                    'state'     => $user->addresses[0]->state,
                    'zip'       => $user->addresses[0]->postal_code,
                    'country'   => "Indonesia"
                ],
                'contact_persons'   => [[
                    'salutation'    => 'Mr',
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'email'         => $user->email,
                    'phone'         => $user->phone,
                    'mobile'        => $user->phone,
                    'is_primary_contact'    => true
                ]]
            ];

            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();

            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'contacts/' . $user->zoho_id . '?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);

            if($request->getStatusCode() == 200){
                $collect = json_decode($request->getBody());

                return $collect;
            }
            else{
                return "Error!";
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > updateUser ".$ex);
            return "Error!";
        }
    }
    //User Stuff Finish

    //Category Stuff Start
    //Category = Item Group
    /**
     * Function to create new Category or Item Group in Zoho.
     *
     * @param Category $category
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createCategory(Category $category)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $productDefault = Product::find(1);
            $items = [
                'name' => $productDefault->name." ".$category->name,
                'rate' => $productDefault->price,
                'purchase_rate' => 0,
            ];
            $jsonData = [
                'group_name'            => $category->name,
                'unit'                  => 'pcs',
                'description'           => $category->slug,
                'items'                 => [
                    $items
                ]
            ];
            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();
//        dd(json_encode($jsonData));
            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'itemgroups?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);
//        dd($request);
            if($request->getStatusCode() == 200 || $request->getStatusCode() == 201){
                $collect = json_decode($request->getBody());
//            dd($collect);

                //Save Zoho Category Id
                $categoryData = Category::find($category->id);
                $categoryData->zoho_item_group_id = $collect->item_group->group_id;
                $categoryData->save();

                return $collect->item_group->group_id;
            }
            else{
                return "Error!";
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > createCategory ".$ex);
            return "Error!";
        }
    }

    /**
     * Function to Update the Category Product Item Groups in Zoho.
     *
     * @param Category $category
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function updateCategory(Category $category)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $jsonData = [
                'group_name'            => $category->name,
                'description'           => $category->slug,
            ];
            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();

            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'itemgroups/' . $category->zoho_item_group_id . '?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);

            if($request->getStatusCode() == 200){
                $collect = json_decode($request->getBody());

                return $collect;
            }
            else{
                return "Error!";
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > updateCategory ".$ex);
            return "Error!";
        }
    }
    //Category Stuff Finish

    //Product Stuff Start
    /**
     * Function to create product (item)
     *
     * @param Product $product
     * @param $zohoGroupId
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createProduct(Product $product, $zohoGroupId)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $jsonData = [
                //'group_id'              => $zohoGroupId,
                'unit'                  => 'pcs',
                'item_type'             => 'inventory',
                'description'           => $product->description,
                'attribute_name1'       => $product->colour,
                'name'                  => $product->name . ' ' . $product->colour,
                'rate'                  => $product->price,
                'initial_stock'         => (int)$product->qty,
                'initial_stock_rate'    => (int)$product->qty,
                'sku'                   => $product->sku
            ];
            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();
//            dd($jsonData);
            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'items?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);

            //return $request;
//            dd($request);

            if($request->getStatusCode() == 200 || $request->getStatusCode() == 201){
                $collect = json_decode($request->getBody());

                //dd($collect, $collect->item->item_id);

                //Save Contact Id
                $productData = Product::find($product->id);
                $productData->zoho_id = $collect->item->item_id;
                $productData->save();

                return $collect;
            }
            else{
                return "Error!";
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > createProduct ".$ex);
            return $ex;
        }
    }

    /**
     * Function to assign item to item Group
     * @param Product $product
     * @param $zohoGroupId
     * @param $zohoGroupName
     * @return bool|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function assignItemToGroup(Product $product, $zohoGroupId, $zohoGroupName){
        try{
            //another Api to Assign Item to Group
            //https://inventory.zoho.com/api/v1/items/grouping/{group_id}?organization_id=XXXXX&authtoken=XXXXXXX
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $jsonData = [
                'group_name'        => $zohoGroupName,
                'attribute_name1'   => $product->colour,
                'items'             => collect([
                    'item_id'           => $product->zoho_id,
                    'sku'               => $product->sku,
                    'name'              => $product->name,
                    'attribute_name1'   => $product->colour
                ])
            ];

            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();

            $request = $client->request('PUT', env('ZOHO_BASE_URL') . 'items/grouping/' . $zohoGroupId . '?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);

            if($request->getStatusCode() == 200 || $request->getStatusCode() == 201){
                $collect = json_decode($request->getBody());

                return $collect;
            }
            else{
                return "Error!";
            }
        }
        catch (\Exception $exception){
            return false;
        }
    }

    /**
     * Function to Update item.
     *
     * @param Product $product
     * @param $zohoGroupId
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function updateProduct(Product $product, $zohoGroupId)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $jsonData = [
                'group_id'              => $zohoGroupId,
                'description'           => $product->description,
                'attribute_name1'       => $product->colour,
                'name'                  => $product->name . ' ' . $product->colour,
                'rate'                  => $product->price,
                'initial_stock'         => 0,
                'sku'                   => $product->sku
            ];
            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();

            $request = $client->request('PUT', env('ZOHO_BASE_URL') . 'items/'. $product->zoho_id .'?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);

            //return $request;

            if($request->getStatusCode() == 200){
                $collect = json_decode($request->getBody());

                return $collect;
            }
            else{
                return "Error!";
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > updateProduct ".$ex);
            return "Error!";
        }
    }

    public static function stockAdjustment(Product $product, $prevQty)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $jsonData = [
                'date'                  => Carbon::now('Asia/Jakarta')->format("Y-m-d"),
                'reason'                => "Stock Written off",
                'adjustment_type'       => "quantity",
                'description'           => "Proses Manual Sell Atau Update Product",
                'line_items'            => [[
                    'item_id'           => $product->zoho_id,
                    'quantity_adjusted' => $prevQty
                ]]
            ];
            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();

            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'inventoryadjustments?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);

            if($request->getStatusCode() == 200 || $request->getStatusCode() == 201){
                $collect = json_decode($request->getBody());

                return $collect;
            }
            else{
                return $request;
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > updateProduct ".$ex);
            return "Error!";
        }
    }
    //Product Stuff Finish

    //Transaction Stuff Start
    //Transaction equals to Sales Order
    /**
     * Function to create Sales Order.
     *
     * @param Order $order
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createSalesOrder(Order $order)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();

            //Create array for line items
            $lineItems = [];
            foreach ($order->order_products as $detail){
                $item = [
                    'item_id'   => $detail->product->zoho_id,
                    'quantity'  => $detail->qty,
                    'rate'      => $detail->price
                ];

                $lineItems[] = $item;
            }


            $dateCarbon = Carbon::parse($order->created_at);
            error_log($dateCarbon->format("Y-m-d\T00:00:00.000\Z"));

            $jsonData = [
                'customer_id'           => $order->user->zoho_id,
                //'salesorder_number'     => $order->order_number,
                //'date'                  => $dateCarbon->format("Y-m-d\T00:00:00.000\Z"),
                'line_items'            => $lineItems,
                'discount'              => $order->voucher_amount,
                'shipping_charge'       => $order->shipping_charge,
                'delivery_method'       => $order->shipping_option,
                'ignore_auto_number_generation' => true
            ];


            error_log(json_encode($jsonData));

            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'salesorders?authtoken=' . $configuration->configuration_value . '&organization_id=' . env('ZOHO_ORGANIZATION_ID'), [
                'form_params' => [
                    'JSONString' => json_encode($jsonData)
                ]
            ]);

            if($request->getStatusCode() == 200 || $request->getStatusCode() == 201){
                $collect = json_decode($request->getBody());

                //dd($collect);

                //Save Contact Id
                $orderData = Order::find($order->id);
                $orderData->zoho_sales_order_id = $collect->salesorder->salesorder_id;
                $orderData->save();

                return $collect;
            }
            else{
                return "Error!";
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > createSalesOrder ".$ex);
            return "Error!";
        }
    }

    /**
     * Function to create invoice from SalesOrder.
     *
     * @param $salesOrderId
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function createInvoice($salesOrderId)
    {
        try{
            $client = new Client([
                'base_uri' => env('ZOHO_BASE_URL')
            ]);

            $configuration = Configuration::where('configuration_key', 'zoho_token')->first();
            $request = $client->request('POST', env('ZOHO_BASE_URL') . 'invoices/fromsalesorder?organization_id=' .
                env('ZOHO_ORGANIZATION_ID') . '&authtoken=' . $configuration->configuration_value . '&salesorder_id=' . $salesOrderId);

            if($request->getStatusCode() == 200){
                return true;
            }
            else{
                return false;
            }
        }
        catch(\Exception $ex){
            Log::error("Zoho.php > createInvoice ".$ex);
            return false;
        }
    }
    //Transaction Stuff Finish

    public static function getBetween($content,$start,$end){
        $r = explode($start, $content);
        if (isset($r[1])){
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }
}
