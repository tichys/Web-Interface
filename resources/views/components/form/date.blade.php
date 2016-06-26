<div class="form-group">
    {{ Form::label($name, null, ['class' => 'control-label']) }}
    {{ Form::date($name, NULL, array_merge(['class' => 'form-control','placeholder' => \Carbon\Carbon::now()], $attributes)) }}
</div>