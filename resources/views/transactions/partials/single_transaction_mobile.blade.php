<span class="float-right">{{ $transaction->amount_string }}</span>
{{ link_to_route('transactions.index', $transaction->date, [
    'date' => $transaction->date_only,
    'month' => $month,
    'year' => $year,
    'category_id' => request('category_id'),
]) }}
<div>
    {{ $transaction->description }}
    <span class="float-right">
        @can('update', $transaction)
            {!! link_to_route(
                'transactions.index',
                __('app.edit'),
                ['action' => 'edit', 'id' => $transaction->id] + request(['month', 'year', 'query', 'category_id']),
                ['id' => 'edit-transaction-'.$transaction->id, 'class' => 'text-danger']
            ) !!}
        @endcan
        @can('create', new App\Transaction)
            | {{ link_to_route(
                'transactions.create',
                __('app.duplicate'),
                [
                    'action' => $transaction->in_out ? 'add-income' : 'add-spending',
                    'original_transaction_id' => $transaction->id,
                    'reference_page' => 'transactions',
                ] + request(['month', 'year', 'query', 'category_id', 'bank_account_id']),
                ['id' => 'duplicate-transaction-'.$transaction->id, 'class' => 'text-danger']
            ) }}
        @endcan
    </span>
</div>
<div style="margin-bottom: 6px;">
    @if ($transaction->loan)
        @php
            $loanRoute = route('loans.show', $transaction->loan);
        @endphp
        <a href="{{ $loanRoute }}">{!! optional($transaction->loan)->type_label !!}</a>
    @endif
    @if ($transaction->partner)
        @php
            $partnerRoute = route('partners.show', [
                $transaction->partner_id,
                'start_date' => $startDate,
                'end_date' => $year.'-'.$month.'-'.date('t'),
            ]);
        @endphp
        <a href="{{ $partnerRoute }}">{!! optional($transaction->partner)->name_label !!}</a>
    @endif
    @if ($transaction->category)
        @php
            $categoryRoute = route('categories.show', [
                $transaction->category_id,
                'start_date' => $startDate,
                'end_date' => $year.'-'.$month.'-'.date('t'),
            ]);
        @endphp
        <a href="{{ $categoryRoute }}">{!! optional($transaction->category)->name_label !!}</a>
    @endif
</div>
