<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function process(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return view('home');
        }

        $queryLower = strtolower($query);

        $result = null;
        $sql = null;
        $sqlDisplay = null;
        $message = null;

        $category = null;
        $productType = null;
        $season = null;
        $action = null;
        $days = null;

        // =========================
        // PRODUCT MAP
        // =========================
        $productMap = [
            'moisturiser' => 'cream',
            'moisturizer' => 'cream',
            'cream' => 'cream',
            'shampoo' => 'shampoo',
            'conditioner' => 'conditioner',
            'oil' => 'oil',
            'mask' => 'mask',
            'cleanser' => 'cleanser',
            'serum' => 'serum',
            'sunscreen' => 'sunscreen'
        ];

        foreach ($productMap as $key => $value) {
            if (str_contains($queryLower, $key)) {
                $productType = $value;
                break;
            }
        }

        // CATEGORY
        if (str_contains($queryLower, 'hair')) $category = 'haircare';
        elseif (str_contains($queryLower, 'skin')) $category = 'skincare';

        // SEASON
        if (str_contains($queryLower, 'summer')) $season = 'summer';
        elseif (str_contains($queryLower, 'winter')) $season = 'winter';

        // ACTION
        if (str_contains($queryLower, 'compare')) {
            $action = 'compare';
        } elseif (
            str_contains($queryLower, 'sales') ||
            str_contains($queryLower, 'count') ||
            str_contains($queryLower, 'how many')
        ) {
            $action = 'count';
        }

        // DAYS
        if (preg_match('/(\d+)/', $queryLower, $matches)) {
            $days = (int)$matches[1];
        }

        // =========================
        // QUERY LOGIC
        // =========================

        // 🔥 COMPARE
        if ($action === 'compare') {

            $sql = "
                SELECT products.category, 
                COALESCE(SUM(order_items.quantity * products.price),0) AS total_sales
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.category IN ('skincare','haircare')
                GROUP BY products.category
            ";

            $sqlDisplay = "
                SELECT products.category, 
                COALESCE(SUM(order_items.quantity * products.price),0) AS total_sales
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.category IN ('skincare','haircare')
                GROUP BY products.category;
            ";

            $result = DB::select($sql);
        }

        // 🔥 CATEGORY + TYPE + DAYS
        elseif ($category && $productType && $days) {

            $sql = "
                SELECT COALESCE(SUM(order_items.quantity),0) AS total_quantity
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.category = ?
                AND products.type LIKE ?
                AND orders.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ";

            $params = [$category, "%$productType%", $days];

            $sqlDisplay = "
                SELECT COALESCE(SUM(order_items.quantity),0)
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.category = '$category'
                AND products.type LIKE '%$productType%'
                AND orders.created_at >= DATE_SUB(NOW(), INTERVAL $days DAY);
            ";

            $result = DB::select($sql, $params);
        }

        // 🔥 CATEGORY + DAYS (FIXED)
        elseif ($category && $days) {

            $sql = "
                SELECT COALESCE(SUM(order_items.quantity),0) AS total_quantity
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.category = ?
                AND orders.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ";

            $params = [$category, $days];

            $sqlDisplay = "
                SELECT COALESCE(SUM(order_items.quantity),0)
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.category = '$category'
                AND orders.created_at >= DATE_SUB(NOW(), INTERVAL $days DAY);
            ";

            $result = DB::select($sql, $params);
        }

        // 🔥 TYPE + DAYS
        elseif ($productType && $days) {

            $sql = "
                SELECT COALESCE(SUM(order_items.quantity),0) AS total_quantity
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.type LIKE ?
                AND orders.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ";

            $params = ["%$productType%", $days];

            $sqlDisplay = "
                SELECT COALESCE(SUM(order_items.quantity),0)
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                JOIN orders ON order_items.order_id = orders.id
                WHERE products.type LIKE '%$productType%'
                AND orders.created_at >= DATE_SUB(NOW(), INTERVAL $days DAY);
            ";

            $result = DB::select($sql, $params);
        }

        // 🔥 TYPE ONLY
        elseif ($productType) {

            $sql = "
                SELECT COALESCE(SUM(order_items.quantity),0) AS total_quantity
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                WHERE products.type LIKE ?
            ";

            $params = ["%$productType%"];

            $sqlDisplay = "
                SELECT COALESCE(SUM(order_items.quantity),0)
                FROM order_items
                JOIN products ON order_items.product_id = products.id
                WHERE products.type LIKE '%$productType%';
            ";

            $result = DB::select($sql, $params);
        }

        // ❌ FALLBACK
        else {
            $message = "Try: 'skincare sales in 20 days', 'compare skincare and haircare'";
        }

        if ($result && isset($result[0]->total_quantity) && $result[0]->total_quantity == 0) {
            $message = "No data found.";
        }

        $history = session()->get('history', []);

        if ($query && !in_array($query, $history)) {
            array_unshift($history, $query);
        }

        $history = array_slice($history, 0, 5);

        session()->put('history', $history);

        return view('home', compact('query', 'sqlDisplay', 'result', 'message', 'history'));
    }

    public function tables()
    {
        $products = DB::table('products')->limit(10)->get();
        $orders = DB::table('orders')->limit(10)->get();
        $orderItems = DB::table('order_items')->limit(10)->get();

        return view('tables', compact('products', 'orders', 'orderItems'));
    }
}