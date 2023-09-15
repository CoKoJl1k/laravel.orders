<?php

namespace App\Http\Controllers;

use App\Mail\ReplyEmail;
use App\Models\Order;
use App\Services\JWTService;
use App\Services\OrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public OrdersService $ordersService;
    public JWTService $jwtService;

    public function __construct(OrdersService $ordersService,JWTService $jwtService)
    {
        $this->ordersService = $ordersService;
        $this->jwtService = $jwtService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function index(Request $request)
    {
        $status = $request->query('status');
        $orders = Order::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->get();
        return response()->json($orders);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */

    public function update(Request $request, $id)
    {
        $errors = $this->ordersService->validateUpdate($request);
        if(!empty($errors['message'])) {
            return response()->json(['status' => 'fail', 'message' => $errors['message']], 400);
        }
        $user = $this->jwtService->getUserByToken($request);

        if ($user->role === 'admin')  {
            $order = Order::findOrFail($id);
            $order->status = 'Resolved';
            $order->comment = $request->input('comment');
            $order->updated_at = now();
        } else {
            return response()->json(['status' => 'fail', 'message' => 'You dont have permission.'], 401);
        }

        try {
            $order->save();

            $replyEmail = new ReplyEmail($order->name, $order->id, 'Ответ по заявке', 'emails.replyOrderEmail', $order->comment);
            Mail::to($order->email)->send($replyEmail);

            return response()->json($order, 201);
        } catch (\Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 400);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request)
    {
        $errors = $this->ordersService->validateStore($request);
        if(!empty($errors['message'])) {
            return response()->json(['status' => 'fail', 'message' => $errors['message']], 400);
        }
        $order = new Order;
        $order->name = $request->input('name');
        $order->email = $request->input('email');
        $order->message = $request->input('message');

        try {
            $order->save();
            $replyEmail = new ReplyEmail($order->name, $order->id, 'Создание заявки', 'emails.createOrderEmail');
            Mail::to($order->email)->send($replyEmail);

            return response()->json($order, 201);
        } catch (\Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 400);
        }
    }
}
