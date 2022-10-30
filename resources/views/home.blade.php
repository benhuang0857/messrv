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

                    <h4>歡迎您 {{$Data['User']->name}}</h4>

                    <div class="panel-group" id="accordion">
                        @foreach ($Data['Batches'] as $batch)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" 
                                        href="#collapse{{$batch->id}}">母批：{{$batch->run_id}} 批號：{{$batch->batch_code}} 製程：{{$batch->ProdProcessesList->Processes->process_code}} 產品: {{$batch->ProdProcessesList->Products->product_name}}
                                        <span class="badge badge-secondary" style="background:<?php echo $Data['Color'][$batch->state]?>">{{$Data['States'][$batch->state]}}</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$batch->id}}" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                    <form>
                                        <input type="text" name="batchid" class="form-control" value="{{$batch->id}}" style="display:none" readonly>
                                        <div class="form-group">
                                            <label for="order">製程順序</label>
                                            <input type="text" name="order" class="form-control" value="{{$batch->ProdProcessesList->Processes->process_code}}" readonly>
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
                                            <label for="tool">員工</label>
                                            <select class="form-control" name="doer_id">
                                            <option value="Ng">未設定</option>
                                            @foreach ($Data['Doers'] as $doer)
                                                <option value="{{$doer->id}}">{{$doer->name}}</option>
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

                                        <div class="form-group">
                                            <label for="hold_reason">暫停原因</label>
                                            <select class="form-control" name="hold_reason">
                                                <option value="None">無</option>
                                                <option value="PM">機台PM</option>
                                                <option value="Error">機台異常</option>
                                                <option value="Break">休息</option>
                                            </select>
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
        var tool = $(event).closest('form').find('select[name="tool"]').val();
        var doer = $(event).closest('form').find('select[name="doer_id"]').val();

        if(tool != 'Ng' || doer != 'Ng')
        {
            $.ajax({
                type: "GET",
                url: "/ajax/process_start",
                dataType: "json",
                data:{
                    batchId: batchId,
                    doer_id: doer,
                    tool: tool,
                },
                success: function (response) {
                    if(response == 'ok')
                    {
                        alert('開始執行');
                        $(event).closest('.panel').css("background-color", "#e6fdd8")
                    }
                    else
                    {
                        alert('您無法執行此操作');
                    }
                },
                error: function (thrownError) {
                    console.log(thrownError);
                }
            });
        }
        else
        {
            alert('未設定機台或員工');
        }
        
    }

    function hold(event)
    {
        var batchId = $(event).closest('form').find('input[name="batchid"]').val();
        var scrap = $(event).closest('form').find('input[name="scrap"]').val();
        var tool = $(event).closest('form').find('select[name="tool"]').val();
        var doer = $(event).closest('form').find('select[name="doer_id"]').val();
        var holdReason = $(event).closest('form').find('select[name="hold_reason"]').val();

        $.ajax({
            type: "GET",
            url: "/ajax/process_hold",
            dataType: "json",
            data:{
                batchId: batchId,
                doer_id: doer,
                tool: tool,
                holdReason: holdReason
            },
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
