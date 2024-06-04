<?php

namespace App\Http\Controllers\Analyze;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Shipping;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AnalyzeController extends Controller
{
    public function age(){
        // استعلام للحصول على متوسط العمر
        $averageAge = User::avg('age');

        // استعلام للحصول على عدد الأشخاص في فئات العمر كل عشر سنوات
        $ageStatistics = User::select(\DB::raw('FLOOR(age/10)*10 as age_group'), \DB::raw('count(*) as count'))
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get()
            ->makeHidden(['roles','permissions']);
        // حساب إجمالي عدد المستخدمين
        $totalUsers = User::count();

        // حساب نسبة كل age_group من الكل
        $ageStatisticsWithPercentage = $ageStatistics->map(function ($item) use ($totalUsers) {
            $item['percentage'] = ($item['count'] / $totalUsers) * 100;
            return $item;
        });
            $ageStatistics = [
                'average_age' => $averageAge,
                'age_statistics' => $ageStatisticsWithPercentage
                ];
        return $this->apiResponse(200,__('lang.Successfully'),null,$ageStatistics);
    }

    public function gender(){
        // استعلام للحصول على متوسط العمر
        $totalUsers = User::count();

        $genderStatistics = User::select('gender', \DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get()
            ->makeHidden(['roles','permissions']);

        // حساب نسبة كل نوع من الكل
        $genderStatisticsWithPercentage = $genderStatistics->map(function ($item) use ($totalUsers) {
            $item['percentage'] = ($item['count'] / $totalUsers) * 100;
            return $item;
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$genderStatisticsWithPercentage);
    }

    public function best_sell_categories(){
        $topCategories = Category::select(
            'categories.id',
            'categories.name',
            DB::raw('COUNT(order_items.id) as order_items_count'),
            DB::raw('SUM(order_items.total_price) as total_profit')
        )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('product_details', 'products.id', '=', 'product_details.product_id')
            ->leftJoin('product_sizes', 'product_details.id', '=', 'product_sizes.product_detail_id')
            ->leftJoin('order_items', 'product_sizes.id', '=', 'order_items.product_size_id')
            ->whereNull('categories.deleted_at')
            ->groupBy('categories.id', 'categories.name') // أضف هذا
            ->orderByDesc('order_items_count')
            ->limit(25)
            ->get();


        return $this->apiResponse(200,__('lang.Successfully'),null,$topCategories);
    }

    public function best_sell_delivered_order(){
        $mostDeliveredProducts = ProductSize::withCount(['order_items as total_deliveries' => function ($query) {
            $query->whereHas('order', function ($query) {
                $query->where('status', 'Delivered');
            });
        }])
        ->with(['product_detail','product_detail.product'])
        ->orderByDesc('total_deliveries')
        ->take(25) // Adjust the number as needed
        ->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$mostDeliveredProducts);
    }

    public function best_and_bad_products(){
        // In your controller or service
        $highestRatedProducts = Product::withCount(['rates as total_ratings', 'rates as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rate), 0)'));
        }])
        ->orderByDesc('average_rating')
        ->take(10) // Adjust the number as needed
        ->get();

        $lowestRatedProducts = Product::withCount(['rates as total_ratings', 'rates as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rate), 0)'));
        }])
        ->orderBy('average_rating')
        ->take(10) // Adjust the number as needed
        ->get();

        return $this->apiResponse(200,__('lang.Successfully'),null,['highest_rated_products' => $highestRatedProducts,'lowest_rated_products' => $lowestRatedProducts],);
    }

    public function best_sell_order_addresses(){
        $shippingOrdersCount = Shipping::withCount(['orders as total_orders'])
        ->orderByDesc('total_orders')
        ->take(25) // Adjust the number as needed
        ->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$shippingOrdersCount);
    }

    public function best_sell_products_order_base_time(Request $request){
        // In your controller or service
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d H:i:s'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d H:i:s'));

        $productsSoldInRange = ProductSize::whereHas('order_items.order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with(['product_detail','product_detail.product'])
            ->orderBy('created_at', 'desc')
            ->take(25) // Adjust the number as needed
            ->get();
        return $this->apiResponse(200,__('lang.Successfully'),null,$productsSoldInRange);
    }

    public function best_views_products(){
        // In your controller or service
        $highestRatedProducts = Product::
        orderBy('views', 'desc')
        ->take(25) // Adjust the number as needed
        ->get();

        return $this->apiResponse(200,__('lang.Successfully'),null,$highestRatedProducts);
    }

    public function total_amount(){
        $totalAmount = Order::get();
        $totalAmount = $totalAmount->sum(function ($order) {
            return $order->total_price;
        });
        return $this->apiResponse(200,__('lang.Successfully'),null,$totalAmount);
    }
}
