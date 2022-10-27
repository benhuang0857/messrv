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
                                        href="#collapse{{$batch->id}}">批號：{{$batch->batch_code}} 
                                        <span class="badge badge-secondary" style="background:<?php echo $Data['Color'][$batch->state]?>">{{$Data['States'][$batch->state]}}</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$batch->id}}" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                    <form>
                                        <input type="text" name="batchid" class="form-control" value="{{$batch->id}}" readonly>
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
                                        <a type="submit" class="btn btn-primary" onclick="process(this)">執行</a>
                                        <a type="submit" class="btn btn-danger" onclick="hold(this)">暫停</a>
                                        <a type="submit" class="btn btn-success" onclick="complete(this)">完成</a>
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

<script>
    function process(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();

        $.ajax({
            type: "GET",
            url: "/ajax/process_start",
            dataType: "json",
            data:{batchId: batchId},
            success: function (response) {
                alert('開始執行');
                $(event).closest('.panel').css("background-color", "#e6fdd8")
            },
            error: function (thrownError) {
                console.log(thrownError);
            }
        });
    }

    function hold(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();

        $.ajax({
            type: "GET",
            url: "/ajax/process_hold",
            dataType: "json",
            data:{batchId: batchId},
            success: function (response) {
                alert('暫停');
                $(event).closest('.panel').css("background-color", "")
            },
            error: function (thrownError) {
                console.log(thrownError);
            }
        });
    }

    function complete(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();

        $.ajax({
            type: "GET",
            url: "/ajax/process_complete",
            dataType: "json",
            data:{batchId: batchId},
            success: function (response) {
                alert('完成');
                $(event).closest('.panel').css("background-color", "#cbcbcb")
            },
            error: function (thrownError) {
                console.log(thrownError);
            }
        });
    }
    
</script>

@endsection
