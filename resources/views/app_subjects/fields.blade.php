<!-- Subject Name Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('subject_name', 'Subject Name:') !!}
    {!! Form::textarea('subject_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Department Id Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('department_id', 'Department Id:') !!}
    {!! Form::textarea('department_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('appSubjects.index') }}" class="btn btn-default">Cancel</a>
</div>
