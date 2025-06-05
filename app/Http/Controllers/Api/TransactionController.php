<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCreatedMail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions
     */
    public function index(): JsonResponse
    {
        try {
            $transactions = Transaction::with(['product', 'category'])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Transactions retrieved successfully',
                'data' => $transactions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get form data for creating transaction
     */
    public function create(): JsonResponse
    {
        try {
            $products = Product::all();
            $categories = Category::all();
            
            return response()->json([
                'success' => true,
                'message' => 'Form data retrieved successfully',
                'data' => [
                    'products' => $products,
                    'categories' => $categories
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve form data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created transaction
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_number' => 'required|string|max:255',
                'id_product' => 'required|exists:products,id',
                'id_category' => 'required|exists:categories,id',
                'status_payment' => 'required|in:pending,paid,cancel',
                'discount' => 'nullable|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
                'due_date' => 'required|date',
            ]);

            $data = $request->all();
            $data['uuid'] = Str::uuid();
            $data['transaction_code'] = strtoupper(Str::random(5));

            $transaction = Transaction::create($data);

            // Load relationships for response
            $transaction->load(['product', 'category']);

            // Send email notification
            try {
                Mail::to('faris.riskilail@gmail.com')->send(new TransactionCreatedMail($transaction));
            } catch (\Exception $e) {
                \Log::error("Failed to send transaction email: " . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified transaction
     */
    public function show(Transaction $transaction): JsonResponse
    {
        try {
            $transaction->load(['product', 'category', 'account']);
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction retrieved successfully',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get form data for editing transaction
     */
    public function edit(Transaction $transaction): JsonResponse
    {
        try {
            $products = Product::all();
            $categories = Category::all();
            $transaction->load(['product', 'category']);
            
            return response()->json([
                'success' => true,
                'message' => 'Edit form data retrieved successfully',
                'data' => [
                    'transaction' => $transaction,
                    'products' => $products,
                    'categories' => $categories
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve edit form data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified transaction
     */
    public function update(Request $request, Transaction $transaction): JsonResponse
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_number' => 'required|string|max:255',
                'id_product' => 'required|exists:products,id',
                'id_category' => 'required|exists:categories,id',
                'status_payment' => 'required|in:pending,paid,cancel',
                'discount' => 'nullable|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
                'periode_bulan' => 'required|integer|min:1',
            ]);

            $data = $request->all();
            $data['due_date'] = Carbon::now()->addMonths($request->periode_bulan);
            
            $transaction->update($data);
            $transaction->load(['product', 'category']);

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified transaction
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        try {
            $transaction->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history (paid transactions)
     */
    public function history(): JsonResponse
    {
        try {
            $transactions = Transaction::with(['product', 'category'])
                ->where('status_payment', 'paid')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Transaction history retrieved successfully',
                'data' => $transactions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaction history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Provide accounts for all paid transactions
     */
    public function provideAccounts(): JsonResponse
    {
        try {
            $transactions = Transaction::with(['product', 'category'])
                ->where('status_payment', 'paid')
                ->whereNull('id_account') // Only unprocessed transactions
                ->get();

            $provided = [];

            foreach ($transactions as $trx) {
                $accounts = Account::where('id_product', $trx->id_product)
                    ->whereHas('product.categories', function ($q) use ($trx) {
                        $q->where('categories.id', $trx->id_category);
                    })
                    ->where('stock', '>', 0)
                    ->get();

                if ($accounts->count() == 0) {
                    $provided[] = [
                        'transaction_id' => $trx->id,
                        'transaction_code' => $trx->transaction_code,
                        'customer_name' => $trx->customer_name,
                        'status' => 'failed',
                        'note' => 'No accounts available'
                    ];
                    continue;
                }

                $selectedAccount = $accounts->first();
                $selectedAccount->decrement('stock');
                
                // Update transaction with account
                $trx->update(['id_account' => $selectedAccount->id]);

                $provided[] = [
                    'transaction_id' => $trx->id,
                    'transaction_code' => $trx->transaction_code,
                    'customer_name' => $trx->customer_name,
                    'status' => 'success',
                    'account_info' => [
                        'email' => $selectedAccount->email,
                        'number' => $selectedAccount->number,
                        'link' => $selectedAccount->link ?? null,
                        'product' => $selectedAccount->product->title,
                        'category' => $trx->category->name,
                    ]
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Accounts provided successfully',
                'data' => $provided,
                'summary' => [
                    'total_processed' => count($provided),
                    'successful' => count(array_filter($provided, fn($p) => $p['status'] === 'success')),
                    'failed' => count(array_filter($provided, fn($p) => $p['status'] === 'failed'))
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to provide accounts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Provide account for a single transaction
     */
    public function provideSingleAccount(Transaction $transaction): JsonResponse
    {
        try {
            if ($transaction->id_account) {
                return response()->json([
                    'success' => false,
                    'message' => 'This transaction already has an account assigned'
                ], 400);
            }

            $accounts = Account::where('id_product', $transaction->id_product)
                ->whereHas('product.categories', function ($q) use ($transaction) {
                    $q->where('categories.id', $transaction->id_category);
                })
                ->where('stock', '>', 0)
                ->get();

            if ($accounts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No accounts available for this transaction'
                ], 404);
            }

            $selected = $accounts->first();
            $selected->decrement('stock');
            $transaction->update(['id_account' => $selected->id]);
            
            $productTitle = strtolower($selected->product->title ?? '');
            $accountInfo = [];

            if (strpos($productTitle, 'youtube') !== false || strpos($productTitle, 'spotify') !== false) {
                $accountInfo = [
                    'type' => 'link',
                    'link' => $selected->link,
                    'display_info' => 'Link: ' . $selected->link
                ];
            } else {
                $accountInfo = [
                    'type' => 'credentials',
                    'email' => $selected->email,
                    'number' => $selected->number,
                    'display_info' => 'Email: ' . $selected->email . ', Number: ' . $selected->number
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Account assigned successfully',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'customer_name' => $transaction->customer_name,
                    'account_info' => $accountInfo,
                    'product' => $selected->product->title,
                    'category' => $transaction->category->name
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_transactions' => Transaction::count(),
                'pending_transactions' => Transaction::where('status_payment', 'pending')->count(),
                'paid_transactions' => Transaction::where('status_payment', 'paid')->count(),
                'cancelled_transactions' => Transaction::where('status_payment', 'cancel')->count(),
                'total_revenue' => Transaction::where('status_payment', 'paid')->sum('total_price'),
                'transactions_with_accounts' => Transaction::whereNotNull('id_account')->count(),
                'transactions_without_accounts' => Transaction::where('status_payment', 'paid')
                    ->whereNull('id_account')->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}