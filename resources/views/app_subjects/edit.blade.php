@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            App Subject
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($appSubject, ['route' => ['appSubjects.update', $appSubject->id], 'method' => 'patch']) !!}

                        @include('app_subjects.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection