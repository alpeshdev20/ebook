<!-- Department Name Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('department_name', 'Department Name:') !!}
    {!! Form::textarea('department_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Genre Id Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('genre_id', 'Genre Id:') !!}
    {!! Form::textarea('genre_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('appDepartments.index') }}" class="btn btn-default">Cancel</a>
</div>
