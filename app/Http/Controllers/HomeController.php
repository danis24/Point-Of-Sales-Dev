<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Setting;
use App\Category;
use App\Product;
use App\Supplier;
use App\Member;
use App\Selling;
use App\Repayment;
use App\Spending;
use App\Purchase;
use App\Division;
use App\Credit;
use App\PreOrder;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$setting = Setting::find(1);

		$begin = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
		$end = date('Y-m-d');

		$date = $begin;
		$data_date = array();
		$data_income = array();
		$data_income_preorder = array();

		while (strtotime($date) <= strtotime($end)) {
			$data_date[] = (int) substr($date, 8, 2);

			$income = Selling::where('created_at', 'LIKE', "$date%")->sum('pay');
			$income_pre_order = Repayment::where('date', 'LIKE', "$date%")->sum('nominal');
			$data_income[] = (int) $income;
			$data_income_preorder[] = (int) $income_pre_order;

			$date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
		}
		$category = Category::count();
		$product = Product::count();
		$supplier = Supplier::count();
		$member = Member::count();
		
		//Sum All
		$selling_count = Selling::sum("pay");
		$repayment_count = Repayment::sum("nominal");
		$credit_table_count = Credit::sum("nominal");

		//sum permonth
		$selling_count_month = Selling::whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("pay");
		$repayment_count_month = Repayment::whereBetween('date', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("nominal");
		$credit_table_count_month = Credit::whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("nominal");

		$debit_count = "Rp.".currency_format($selling_count+$repayment_count+$credit_table_count);
		$debit_count_month = "Rp.".currency_format($selling_count_month+$repayment_count_month+$credit_table_count_month);

		//Sum All
		$spending_count = Spending::sum("nominal");
		$purchase_count = Purchase::sum("pay");

		//Sum Per month
		$spending_count_month = Spending::whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("nominal");
		$purchase_count_month = Purchase::whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("pay");

		$credit_count = "Rp.".currency_format($spending_count+$purchase_count);
		$credit_count_month = "Rp.".currency_format($spending_count_month+$purchase_count_month);

		$divisions = Division::all();
		$balance = [];
		if($divisions->count() > 0){
			foreach($divisions as $key => $value){
				$selling_division = Selling::where("division_id", $value->id)->sum("pay");
				$repayment_division = Repayment::where("division_id", $value->id)->sum("nominal");
				$spending_division = Spending::where("division_id", $value->id)->sum("nominal");
				$credit_division = Credit::where("division_id", $value->id)->sum("nominal");
				$purchase_division = Purchase::where("division_id", $value->id)->sum("pay");
				//where month
				$selling_division_month = Selling::where("division_id", $value->id)->whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("pay");
				$repayment_division_month = Repayment::where("division_id", $value->id)->whereBetween('date', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("nominal");
				$spending_division_month = Spending::where("division_id", $value->id)->whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("nominal");
				$credit_division_month = Credit::where("division_id", $value->id)->whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("nominal");
				$purchase_division_month = Purchase::where("division_id", $value->id)->whereBetween('created_at', [$begin . " 00:00:00", $end . " 23:59:59"])->sum("pay");


				$division_balance = ($selling_division+$repayment_division+$credit_division)-($spending_division+$purchase_division);
				$balance[] = [
					"division" => $value->name,
					"debit" => "Rp.".currency_format($selling_division+$repayment_division+$credit_division),
					"debit_month" => "Rp.".currency_format($selling_division_month+$repayment_division_month+$credit_division_month),
					"credit" => "Rp.".currency_format($spending_division+$purchase_division),
					"credit_month" => "Rp.".currency_format($spending_division_month+$purchase_division_month),
					"division_balance" => "Rp.".currency_format($division_balance)
				];
			}
		}

		$members = Member::all();
		$topDebt = [];
		if($members->count() > 0){
			foreach($members as $key => $value){
				$preorders = PreOrder::where("member_id", $value->member_id)->get();
				$total_price = 0;
				$total_repayment = 0;
				$total_reminder = 0;
				if($preorders->count() > 0){
					foreach($preorders as $preorder_key => $preorder_value){
						$total_price += $preorder_value->price*$preorder_value->qty;
						$repayment = Repayment::where("pre_order_id", $preorder_value->id)->sum("nominal");
						$total_repayment += $repayment;
					}
				}
				$topDebt[] = [
					"member_name" => $value->member_name,
					"reminder" => ($total_price-$total_repayment)
				];
			}
		}
		$sortDebt = collect($topDebt)->sortBy("reminder")->reverse()->toArray();

		if (Auth::user()->level == 1)
			return view('home.admin', compact('category', 'product', 'supplier', 'member', 'begin', 'end', 'data_income', 'data_income_preorder', 'data_date', 'debit_count', 'credit_count', 'balance', 'sortDebt', 'debit_count_month', 'credit_count_month'));
		else
			return view('home.cashier', compact('setting'));
	}
}
