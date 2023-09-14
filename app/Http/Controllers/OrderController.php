<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $orders = Order::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->get();

        return response()->json($orders);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'Resolved';
        $order->comment = $request->input('comment');
        $order->updated_at = now();
        $order->save();

        // Отправить email пользователю с ответом

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $newOrder = Order::create($validatedData);

        // Отправить email пользователю с подтверждением получения заявки

        return response()->json($newOrder, 201);
    }
}
