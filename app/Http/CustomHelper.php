<?php

use App\Http\AllStatic;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

    function getParentCategory(){
        return [];
        // return App\Models\Category::select(['id','category_name','parent_category','slug'])
        //         ->where('parent_category',AllStatic::$inactive)
        //         ->orderBy('id')->get();
    }

    function getComposition(){
        return DB::table('fabrics')->selectRaw('id as value,fabric_name as name,status')->where('status',AllStatic::$active)->orderBy('id','desc')->get();
    }

    function getColors(){
        return DB::table('colours')->selectRaw('id as value,color_name as name,status')->where('status',AllStatic::$active)->orderBy('id','desc')->get();
    }

    function uniqueString() {
        $letter = 'abcdefghijklmnopqrstuvwxyz0987654321';
        return substr(str_shuffle(strtoupper($letter)), 0, 6);
    }

    function deliveryPosition($status){
        $text = "";
        switch ($status) {
            case 1:
                # code...
                $text = "Processig";
                break;
            case 2:
                # code...
                $text = "On Delivery";
                break;
            case 3:
                # code...
                $text = "Delivered";
                break;
            case 4:
                # code...
                $text = "On Hold";
                break;
            case 5:
                # code...
                $text = "Cancelled";
                break;

            default:
                $text = "Pending";
                break;
        }
        return $text;
    }

    function checkPermission($permission){
        $value = getPermission();
        return in_array($permission,[...$value]);
    }

    function getPermission(){
        // return [];
        // return Cache::remember('admin_permission',60, function () {
            // $value = auth()->guard('admin')->user()->role->role_permission->pluck('slug');
            //  return auth()->guard('admin')->user()->role->role_permission->pluck('slug') ?? [] ;
        // });
    }

    function paymentMethodType($status){
        $text = "";
        switch ($status) {
            case 1:
                # code...
                $text = "MPAY";
                break;
            case 2:
                # code...
                $text = "POS";
                break;
            case 3:
                # code...
                $text = "CCRD";
                break;

            case 4:
                # code...
                $text = "BOD";
                break;

            default:
                $text = "COD";
                break;
        }
        return $text;
    }

    function orderStatus($status){
        $text = "";
        switch ($status) {
            case 0:
                # code...
                $text = "Cancel";
                break;
            case 2:
                # code...
                $text = "On-Hold";
                break;

            default:
                $text = "Active";
                break;
        }
        return $text;
    }
    function paymentStatusText($status){
        $text = "";
        switch ($status) {
            case 1:
                # code...
                $text = "Paid";
                break;
            case 2:
                # code...
                $text = "Failed";
                break;

            case 3:
                # code...
                $text = "Cancel";
                break;

            default:
                $text = "Unpaid";
                break;
        }
        return $text;
    }

    function myFilter($variabl){
        return ($variabl !== NULL && $variabl !== FALSE && $variabl !== "");
    }

    function rangDivider()
    {

    }

?>
