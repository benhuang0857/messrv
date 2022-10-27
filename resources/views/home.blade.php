@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">任務</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    歡迎您 {{$Data['User']->name}}

                    


                    <div class="panel-group" id="accordion">
                        @foreach ($Data['Batches'] as $batch)

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" 
                                        href="#collapseOne">批號：{{$batch->batch_code}} 
                                        <span class="badge badge-secondary" style="background:<?php echo $Data['Color'][$batch->state]?>">{{$Data['States'][$batch->state]}}</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="order">製程順序</label>
                                            <input type="text" name="order" class="form-control" value="{{$batch->ProdProcessesList->order}}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="run">工單</label>
                                            <input type="text" name="run" class="form-control" value="{{$batch->batch_code}}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="tool">機台</label>
                                            <select class="form-control" name="tool">
                                            <option value="Ng">未設定</option>
                                            @foreach ($Data['Tools'] as $tool)
                                                <option value="{{$tool->name}}">{{$tool->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity">執行數量</label>
                                            <input type="number" name="quantity" class="form-control" value="{{$batch->quantity}}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="scrap">報廢</label>
                                            <input type="number" name="scrap" class="form-control" value="{{$batch->scrap}}" >
                                        </div>
                                        <button type="submit" class="btn btn-primary">執行</button>
                                        <button type="submit" class="btn btn-danger">暫停</button>
                                        <button type="submit" class="btn btn-success">完成</button>
                                    </form>
                                    </div>
                                </div>
                            </div>

                            
                        @endforeach
                        
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
