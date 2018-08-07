<?php

namespace Tests\Feature\Transactions;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TransactionEntryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_an_income_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-income', 'month' => $month, 'year' => $year]));

        $this->submitForm(__('transaction.add_income'), [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);

        $this->seePageIs(route('transactions.index', ['month' => $month, 'year' => $year]));
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out'      => 1, // 0:spending, 1:income
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function user_can_create_a_spending_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $this->loginAsUser();
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_spending'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-spending', 'month' => $month, 'year' => $year]));

        $this->submitForm(__('transaction.add_spending'), [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Spending description',
        ]);

        $this->seePageIs(route('transactions.index', ['month' => $month, 'year' => $year]));
        $this->see(__('transaction.spending_added'));

        $this->seeInDatabase('transactions', [
            'in_out'      => 0, // 0:spending, 1:income
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Spending description',
        ]);
    }
}