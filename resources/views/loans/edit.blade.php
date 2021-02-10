@extends('layouts.app')

@section('title', __('loan.edit'))

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        @if (request('action') == 'delete' && $loan)
        @can('delete', $loan)
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">{{ __('loan.delete') }}</h3></div>
                <div class="panel-body">
                    <label class="control-label text-primary">{{ __('loan.name') }}</label>
                    <p>{{ $loan->name }}</p>
                    <label class="control-label text-primary">{{ __('loan.description') }}</label>
                    <p>{{ $loan->description }}</p>
                    {!! $errors->first('loan_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="panel-body text-danger">{{ __('loan.delete_confirm') }}</div>
                <div class="panel-footer">
                    {!! FormField::delete(
                        ['route' => ['loans.destroy', $loan]],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['loan_id' => $loan->id]
                    ) !!}
                    {{ link_to_route('loans.edit', __('app.cancel'), [$loan], ['class' => 'btn btn-default']) }}
                </div>
            </div>
        @endcan
        @else
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('loan.edit') }}</h3></div>
            {{ Form::model($loan, ['route' => ['loans.update', $loan], 'method' => 'patch']) }}
            <div class="panel-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('loan.name')]) !!}
                {!! FormField::textarea('description', ['label' => __('loan.description')]) !!}
            </div>
            <div class="panel-footer">
                {{ Form::submit(__('loan.update'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('loans.show', __('app.cancel'), [$loan], ['class' => 'btn btn-default']) }}
                @can('delete', $loan)
                    {{ link_to_route('loans.edit', __('app.delete'), [$loan, 'action' => 'delete'], ['class' => 'btn btn-danger pull-right', 'id' => 'del-loan-'.$loan->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
@endsection
