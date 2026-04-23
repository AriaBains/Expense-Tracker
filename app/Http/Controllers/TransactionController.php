<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();

        $startMonth = request()->get('start')/* Carbon::now()->startOfMonth() */;
        $endMonth = request()->get('end')/*  Carbon::now()->endOfMonth() */;
        
        $transactions = $user->transactions()->whereBetween('transaction_date', [$startMonth, $endMonth])->get();
        
        return response()->json($transactions);
    }

    public function create(Request $request) {
        
        $input = $request->validate([
            "date" => ['required', 'date'],
            "name" => ['required', 'string', 'max:32'],
            "amount" => ['required', 'numeric'],
            "type" => [Rule::in(["income", "expense"])]
        ]);

        $transaction['name'] = $input['name'];
        $transaction['user_id'] = Auth::id();
        $transaction['type'] = $input['type'];
        $transaction['amount'] = $input['amount'];
        $transaction['transaction_date'] = $input['date'];

        $transaction = Transaction::create($transaction);
        
        return response()->json('created');
    }

    public function update(Request $request, Transaction $transaction) {
        $input = $request->validate([
            "date" => ['required', 'date'],
            "name" => ['required', 'string', 'max:32'],
            "amount" => ['required', 'numeric'],
            "type" => ['required', Rule::in(["income", "expense"])],
            "id" => ['required', 'integer']
        ], [
            "date.required" => "Choose a date",
            "date.date" => "Choose a valid date",
            "name.required" => "Enter the Name",
            "name.string" => "Name can only contain letters or numbers",
            "name.max" => "Name should be less than 32 letters",
            "amount.required" => "Enter Amount",
            "amount.numeric" => "Amount can only contain numbers",
        ]);

        Gate::authorize('update', $transaction);

        $transaction->transaction_date = $input['date'];
        $transaction->name = $input['name'];
        $transaction->amount = $input['amount'];
        $transaction->type = $input['type'];

        $transaction->save();

        /* $input['date'] = Carbon::parse($input['date'])->format('Y-m-d H:i:s'); */

        $response = ["message" => "updated", "transaction" => $input];

        return response()->json($response);
    }

    public function destroy(Transaction $transaction) {

        Gate::authorize('delete', $transaction);

        $transaction->delete();

        return response()->json('deleted');
    }
}
