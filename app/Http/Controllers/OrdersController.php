<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use App\Shipments;
use App\LogShipmentStatus;
use App;

class OrdersController extends Controller
{
    protected   $shopify;

    public function __construct(){

        $this->shopify = App::make('ShopifyAPI', [
                     'API_KEY'       => 'edd5a92b1f8d47400b22964c10858047',
                     'API_SECRET'    => '90dcb42ada32de7196b31a695557e161',
                     'SHOP_DOMAIN'   => 'f9-dev.myshopify.com',
                     'ACCESS_TOKEN'  => '35364181c599e977a5ba4d6b3401068e'
                ]);
    }
   

    public function index()
    {
        $orders  =  Orders::select('orders.id','order_number','email','total_price','order_id','orders.tracking_number','shipments.shipment_status')->leftjoin('shipments','orders.id', 'shipments.fk_order')->get();
        return view('orders',compact('orders'));
    }

    public function show(Article $article)
    {
        //return $article;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['line_items'] = json_encode($input['line_items']);
        $input['order_id'] = $input['id'];
        unset($input['id']);

        $article = Orders::create($input);
        return response()->json('order placed');
    }

    public function accept(Request $request)
    {
        $input = $request->all();
        $tracking_number = rand(1000000,9999999);
        if($input['status'] != ""){
            $order = Orders::find($input['order_id']);
            if($order->tracking_number == ""){
                
                $call = $this->shopify->call(
                  ['URL' => 'admin/orders/' . $order->order_id . '/fulfillments.json',
                  'METHOD' => 'POST',
                  'DATA' => '{
                      "fulfillment": {
                        "tracking_number": "'.$tracking_number.'",
                        "tracking_urls": [
                          "http://localhost/boxee/public/track/'.$tracking_number.'",
                          "http://localhost/boxee/public/track/'.$tracking_number.'"
                        ],
                        "notify_customer": true,
                        "tracking_company":"boxee"
                      }
                    }']);

                $order->tracking_number = $tracking_number;
                $order->save();
                
                $shipment = Shipments::create([
                    'fk_order'        => $input['order_id'],
                    'tracking_number' => $tracking_number,
                    'shipment_status' => 'confirmed',
                    'fulfillment_id' => $call->fulfillment->id
                ]);
            }else{

                $shipment = Shipments::where('fk_order',$input['order_id'])->first();
                $shipment->shipment_status = $input['status'];
                $shipment->save();

                $call = $this->shopify->call(
                  ['URL' => 'admin/orders/' . $order->order_id . '/fulfillments/' . $shipment->fulfillment_id . '/events.json',
                  'METHOD' => 'POST',
                  'DATA' => '{
                        "event": {
                          "status": "'.$input['status'].'"
                        }
                    }']);

                
                
            }
            LogShipmentStatus::create([
                'fk_shipment'           => $shipment->id,
                'shipment_status_from'  => $shipment->shipment_status,
                'shipment_status_to'    => $input['status']
            ]);
            return back()->with('success','Shipment created successfully.');
        }else{
            return back()->with('error','Please select shipment status.');
        }
    }

    public function trackShipment($tracking_number)
    {
        $shipment = Shipments::where('tracking_number',$tracking_number)->first();
        $shipment_logs = [];
        if(count($shipment)>0){
           $shipment_logs = LogShipmentStatus::where('fk_shipment',$shipment->id)->orderBy('id','desc')->get(); 
        }
        return view('track-shipment',compact('tracking_number','shipment_logs'));

    }
    
    public function update(Request $request)
    {
        $input = $request->all();
        dd($input);
        //$article->update($request->all());

        //return response()->json($article, 200);
    }

    public function delete(Article $article)
    {
        //$article->delete();

        //return response()->json(null, 204);
    }
}